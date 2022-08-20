<?php

namespace Shershon\Common\Client\Traits;

use GuzzleHttp\RequestOptions;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use Shershon\Common\Exception\Contract\AlarmExceptionInterface;
use Shershon\Common\Factory\Contract\FactoryInterface;
use Shershon\Common\Http\ClientWrapper;
use Shershon\Common\Service\Contract\ResponseHandlerInterface;
use Shershon\Common\Util\AlarmHttpEvent;

trait ClientTrait
{

    /**
     * @var FactoryInterface
     */
    protected $factory;

    protected $timeout = 3;

    /**
     * @var array
     */
    protected $clientConfig = [];

    /**
     * @var ResponseHandlerInterface|null
     */
    protected $responseHandler = null;

    public function __construct(FactoryInterface $factory, $config = [])
    {
        $this->factory      = $factory;
        $this->clientConfig = $config;

        if ($this instanceof ResponseHandlerInterface) {
            $this->setResponseHandler($this);
        }
    }

    protected function factory(): FactoryInterface
    {
        return $this->factory;
    }

    protected function client(): ClientWrapper
    {
        return new ClientWrapper($this->factory, $this->factory()->newHttpClient($this->getClientConfig()), $this->logger());
    }

    /**
     * @param ResponseHandlerInterface $responseHandler
     * @return $this
     */
    public function setResponseHandler(ResponseHandlerInterface $responseHandler)
    {
        $this->responseHandler = $responseHandler;
        return $this;
    }

    /**
     * @return ResponseHandlerInterface|null
     */
    protected function getResponseHandler()
    {
        return $this->responseHandler;
    }

    /**
     * @param array $clientConfig
     * @return self
     */
    public function setClientConfig(array $clientConfig)
    {
        $this->clientConfig = $clientConfig;
        return $this;
    }

    protected function getClientConfig(): array
    {
        return $this->clientConfig ?? [];
    }

    protected function logger(): LoggerInterface
    {
        return $this->factory()->getLogger();
    }

    public function getBaseQueryParams(): array
    {
        return [];
    }

    public function getBaseHeaders(): array
    {
        return [];
    }

    protected function getTimeout()
    {
        return $this->getClientConfig()['timeout'] ?? $this->timeout;
    }

    public function getBaseOptions(): array
    {
        return [
            RequestOptions::TIMEOUT => $this->getTimeout(),
        ];
    }

    protected function parametersAppend($queryParams = [], $headers = [], $options = []): array
    {
        return [
            array_merge($this->getBaseQueryParams(), $queryParams),
            array_merge($this->getBaseHeaders(), $headers),
            array_merge($this->getBaseOptions(), $options),
        ];
    }

    /**
     * @param $url
     * @param array $queryParams
     * @param array $headers
     * @param array $options
     * @return mixed
     * @throws Throwable
     */
    public function get($url, $queryParams = [], $headers = [], $options = [])
    {
        try {
            list($queryParams, $headers, $options) = $this->parametersAppend($queryParams, $headers, $options);
            $resp = $this->client()->get($url, $queryParams, $headers, $options);
            return $this->parseResponse($resp);
        } catch (Throwable $ex) {
            if ($ex instanceof AlarmExceptionInterface && !$ex->isAlarm()) {
                throw $ex;
            }
            $this->factory()->getEventDispatcher()->dispatch(new AlarmHttpEvent(
                __FUNCTION__,
                $url,
                func_get_args(),
                $resp ?? null,
                $ex
            ));
            throw $ex;
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
     * @throws Throwable
     */
    public function post($url, $queryParams = [], $postData = [], $bodyFormat = ClientWrapper::BODY_FORMAT_FORM, $headers = [], $options = [])
    {
        try {
            list($queryParams, $headers, $options) = $this->parametersAppend($queryParams, $headers, $options);
            $resp = $this->client()->post($url, $queryParams, $postData, $bodyFormat, $headers, $options);
            return $this->parseResponse($resp);
        } catch (Throwable $ex) {
            if ($ex instanceof AlarmExceptionInterface && !$ex->isAlarm()) {
                throw $ex;
            }
            $this->factory()->getEventDispatcher()->dispatch(new AlarmHttpEvent(
                __FUNCTION__,
                $url,
                func_get_args(),
                $resp ?? null,
                $ex
            ));
            throw $ex;
        }
    }

    /**
     * @param string $response
     * @return mixed
     * @throws Throwable
     */
    protected function parseResponse($response)
    {
        try {
            $handler = $this->getResponseHandler();
            return $handler instanceof ResponseHandlerInterface ? $handler->handleResponse($response) : $response;
        } catch (Throwable $ex) {
            if ($this->config->isThrowable()) {
                throw $ex;
            }
            return false;
        }
    }

    /**
     * @param string|UriInterface $url
     * @param array $queryParams
     * @param array $postData
     * @param array $headers
     * @param array $options
     * @return bool|mixed
     */
    public function postJson($url, $queryParams = [], $postData = [], $headers = [], $options = [])
    {
        return $this->post($url, $queryParams, $postData, ClientWrapper::BODY_FORMAT_JSON, $headers, $options);
    }

    /**
     * @param string|UriInterface $url
     * @param array $queryParams
     * @param array $postData
     * @param array $headers
     * @param array $options
     * @return bool|mixed
     */
    public function postForm($url, $queryParams = [], $postData = [], $headers = [], $options = [])
    {
        return $this->post($url, $queryParams, $postData, ClientWrapper::BODY_FORMAT_FORM, $headers, $options);
    }

    /**
     * @param string|UriInterface $url
     * @param array $queryParams
     * @param array $postData
     * @param array $headers
     * @param array $options
     * @return bool|mixed
     */
    public function postXml($url, $queryParams = [], $postData = [], $headers = [], $options = [])
    {
        return $this->post($url, $queryParams, $postData, ClientWrapper::BODY_FORMAT_XML, $headers, $options);
    }

    /**
     * @param string|UriInterface $url
     * @param array $queryParams
     * @param array $postData
     * @param array $headers
     * @param array $options
     * @return bool|mixed
     */
    public function postMultipart($url, $queryParams = [], $postData = [], $headers = [], $options = [])
    {
        return $this->post($url, $queryParams, $postData, ClientWrapper::BODY_FORMAT_MULTIPART, $headers, $options);
    }

}