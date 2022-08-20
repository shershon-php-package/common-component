<?php


namespace Shershon\Common\Http;


use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use Shershon\Common\Factory\Contract\FactoryInterface;
use Shershon\Common\Util\AlarmHttpEvent;

class ClientWrapper
{

    const BODY_FORMAT_JSON      = 'json';
    const BODY_FORMAT_XML       = 'xml';
    const BODY_FORMAT_FORM      = 'form';
    const BODY_FORMAT_MULTIPART = 'multipart';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    public function __construct(FactoryInterface $factory, Client $client, LoggerInterface $logger)
    {
        $this->factory = $factory;
        $this->client  = $client;
        $this->logger  = $logger;
    }

    /**
     * @param $url
     * @param array $queryParams
     * @param array $headers
     * @param array $options
     * @return mixed
     */
    public function get($url, $queryParams = [], $headers = [], $options = [])
    {
        try {
            $options[RequestOptions::QUERY] = $queryParams;
            $options                        = $this->buildOptions($options, $headers);
            $this->logger->debug("=====GET=====", [
                'url'     => $url,
                'options' => $options,
            ]);
            $resp = $this->client->get($url, $options);
            return $this->parseResponse($resp);
        } catch (Throwable $ex) {
            $this->factory->getEventDispatcher()->dispatch(new AlarmHttpEvent(
                __FUNCTION__,
                $url,
                $options,
                $resp ?? null,
                $ex
            ));
            $this->logger->error("REQUEST FAILURE! [{$ex->getCode()}]{$ex->getMessage()}", ['throwable' => $ex]);
            return false;
        }
    }

    /**
     * @param $url
     * @param array $queryParams
     * @param array $postData
     * @param string $bodyFormat
     * @param array $headers
     * @param array $options
     * @return mixed
     */
    public function post($url, $queryParams = [], $postData = [], $bodyFormat = 'form', $headers = [], $options = [])
    {
        try {
            !empty($queryParams) && $options[RequestOptions::QUERY] = $queryParams;
            switch (strtolower($bodyFormat)) {
                case self::BODY_FORMAT_JSON:
                    $options[RequestOptions::JSON] = $postData;
                    break;
                case self::BODY_FORMAT_FORM:
                case 'form_params':
                    $options[RequestOptions::FORM_PARAMS] = $postData;
                    break;
                case self::BODY_FORMAT_MULTIPART:
                case 'multipart/form-data':
                    $options[RequestOptions::MULTIPART] = $postData;
                    break;
                case self::BODY_FORMAT_XML:
                    $options[RequestOptions::BODY] = $postData;
                    $headers['Content-Type']       = 'text/xml; charset=UTF-8';
                    break;
                default:
                    $options[RequestOptions::BODY] = $postData;
                    break;
            }
            $options = $this->buildOptions($options, $headers);
            $this->logger->debug("=====POST=====", [
                'url'     => $url,
                'options' => $options,
            ]);
            $resp = $this->client->post($url, $options);
            return $this->parseResponse($resp);
        } catch (Throwable $ex) {
            $this->factory->getEventDispatcher()->dispatch(new AlarmHttpEvent(
                __FUNCTION__,
                $url,
                $options,
                $resp ?? null,
                $ex
            ));
            $this->logger->error("REQUEST FAILURE! [{$ex->getCode()}]{$ex->getMessage()}", ['throwable' => $ex]);
            return false;
        }
    }

    /**
     * @param ResponseInterface $resp
     * @return string
     * @throws Exception
     */
    protected function parseResponse(ResponseInterface $resp)
    {
        if ($resp->getStatusCode() != '200') {
            throw new Exception($resp->getStatusCode(), $resp->getReasonPhrase());
        }
        // auto decode
        $body = (string)$resp->getBody();
        $this->logger->debug("====REQUEST SUCCESS!====RESP====" . $body);
        return $body;
    }

    protected function buildOptions($opts = [], $headers = [])
    {
        return array_merge($opts, [
            RequestOptions::HEADERS => $headers,
        ]);
    }

}