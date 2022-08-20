<?php

namespace Shershon\Common\Adapter\Hyperf;

use GuzzleHttp\Client;
use Hyperf\Guzzle\HandlerStackFactory;
use Psr\Log\LoggerInterface;
use Shershon\Common\Factory\Traits\FactoryTrait;

trait HyperfFactoryTrait
{

    use FactoryTrait;

    public function getLogger(): LoggerInterface
    {
        return $this->getContainer()->get(\Hyperf\Logger\LoggerFactory::class)->get();
    }

    public function newHttpClient($cfg = []): Client
    {
        return HyperfHttpClientFactory::newHttpClient($this, $cfg);
    }

    protected function getHttpPoolConfig($cfg = [])
    {
        return $cfg['pool'] ?? [];
    }

}