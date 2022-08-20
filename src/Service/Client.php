<?php

namespace Shershon\Common\Service;

use Exception;
use Shershon\Common\Exception\ResponseException;
use Shershon\Common\Client\Traits\ClientServiceTrait;
use Shershon\Common\Client\Traits\JsonClientTrait;
use Shershon\Common\Factory\Contract\FactoryInterface;
use Shershon\Common\Service\Contract\ResponseHandlerInterface;
use Shershon\Common\Service\Contract\ServiceConfigInterface;

class Client implements ResponseHandlerInterface
{

    use JsonClientTrait, ClientServiceTrait {
        ClientServiceTrait::getClientConfig insteadof JsonClientTrait;
        ClientServiceTrait::setClientConfig insteadof JsonClientTrait;
        JsonClientTrait::handleResponse as protected jsonHandleResponse;
        JsonClientTrait::__construct as private traitConstruct;
    }

    const ERRNO_SUCCESS = 20000;

    protected $config;

    public function __construct(FactoryInterface $factory, ?ServiceConfigInterface $config = null)
    {
        $this->traitConstruct($factory);
        $this->config = is_null($config)
            ? getAbstractConcrete($factory->getContainer(), ServiceConfigInterface::class, DefaultServiceConfig::class)
            : $config;
    }

    protected function getBaseUrl(): string
    {
        return $this->getClientConfig()['base_url'] ?? '';
    }

    protected function getApiUrl(string $url): string
    {
        return $this->getBaseUrl() . $url;
    }

    /**
     * @param string $response
     * @return bool|mixed
     * @throws Exception
     */
    public function handleResponse($response)
    {
        $resp = $this->jsonHandleResponse($response);
        if (!is_array($resp)) {
            throw new ResponseException(0, "invalid content", $response);
        }
        if ($resp['code'] != static::ERRNO_SUCCESS) {
            throw new ResponseException($resp['code'], $resp['msg'], $response);
        }
        return $resp['data'];
    }
}
