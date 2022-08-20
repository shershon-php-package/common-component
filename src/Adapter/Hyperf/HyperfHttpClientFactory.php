<?php


namespace Shershon\Common\Adapter\Hyperf;


use GuzzleHttp\HandlerStack;
use Shershon\Common\Factory\Contract\FactoryInterface;
use Shershon\Common\Http\HttpClientFactory;

class HyperfHttpClientFactory extends HttpClientFactory
{

    protected static function handler(FactoryInterface $factory, $cfg = [], $handler = null): HandlerStack
    {
        $stackFactory = $factory->getContainer()->get(\Hyperf\Guzzle\HandlerStackFactory::class);

        $stack = $stackFactory->create(static::getHttpPoolConfig($cfg));

        return parent::handler($factory, $cfg, $stack);
    }

    protected static function getHttpPoolConfig($cfg = [])
    {
        return $cfg['pool'] ?? [];
    }

}