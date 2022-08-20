<?php

namespace Shershon\Common\Factory\Contract;

use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

interface FactoryInterface
{

    public function getContainer(): ContainerInterface;

    public function getCache(): CacheInterface;

    public function getLogger(): LoggerInterface;

    public function newHttpClient($cfg = []): Client;

    public function getEventDispatcher(): EventDispatcherInterface;

    public function makeEntity($class, $properties = []);

    /**
     * 需要重写
     * @param string $name
     * @param mixed $default [optional]
     * @return mixed
     */
    public function config($name, $default = null);

}