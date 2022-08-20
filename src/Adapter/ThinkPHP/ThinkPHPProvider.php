<?php

namespace Shershon\Common\Adapter\ThinkPHP;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Shershon\Common\Util\Support;

class ThinkPHPProvider
{

    public function __construct()
    {
        $list = Support::listDefaultDependent();

        $list = array_merge($list, [
            EventDispatcherInterface::class => EventDispatcher::class,
            ContainerInterface::class       => function () {
                return Support::container();
            },
            CacheInterface::class           => "cache",
            LoggerInterface::class          => "log",
        ]);

        foreach ($list as $key => $value) {
            bind($key, $value);
        }
    }

}
