<?php


namespace Shershon\Common\Http;


use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Shershon\Common\Factory\Contract\FactoryInterface;

class HttpClientFactory
{

    protected static $middleware = [
        'log' => ['name' => 'log']
    ];

    public static function newHttpClient(FactoryInterface $factory, $cfg = []): Client
    {
        return new Client(static::buildHttpClientConfig($factory, $cfg));
    }

    protected static function buildHttpClientConfig(FactoryInterface $factory, $cfg = [], $handler = null): array
    {
        $config            = [];
        $config['handler'] = static::handler($factory, $cfg, $handler);
        static::middleware($factory, $config['handler'], $cfg);
        return $config;
    }

    protected static function handler(FactoryInterface $factory, $cfg = [], $handler = null): HandlerStack
    {
        return !$handler instanceof HandlerStack ? HandlerStack::create($handler) : $handler;
    }

    protected static function middleware(FactoryInterface $factory, HandlerStack $handler, $cfg = [])
    {
        $cfgMiddleware = array_merge(self::$middleware, $cfg['middleware'] ?? []);
        foreach ($cfgMiddleware as $name => $c) {
            empty($c['name']) && $c['name'] = $name;
            $handler->push(MiddlewareFactory::createMiddleware($factory, $c), $name);
        }
    }

}