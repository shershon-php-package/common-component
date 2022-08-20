<?php


namespace Shershon\Common\Http;

use Psr\Log\LogLevel;
use Shershon\Common\Factory\Contract\FactoryInterface;
use Shershon\Common\Http\Contract\LogFormat;

class MiddlewareFactory
{

    public static function createMiddleware(FactoryInterface $factory, $cfg): ?callable
    {
        $handler = $cfg['name'];
        if (!method_exists(static::class, $handler)) {
            return null;
        }
        return call_user_func([static::class, $handler], $factory, $cfg);
    }

    public static function log(FactoryInterface $factory, $cfg)
    {
        return Middleware::log(
            $factory->getLogger(),
            getAbstractConcrete($factory->getContainer(), LogFormat::class, Format::class),
            $cfg['level'] ?? LogLevel::INFO
        );
    }

    public static function ase(FactoryInterface $factory, $cfg)
    {
        return Middleware::aes($cfg['secret'] ?? '011ec47c909e20f9efaab31bfb156b31');
    }

}