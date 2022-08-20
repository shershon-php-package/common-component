<?php

namespace Shershon\Common\Factory\Traits;

use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Shershon\Common\Http\HttpClientFactory;
use Shershon\Common\Util\NullEventDispatcher;
use Shershon\Common\Util\Support;

trait FactoryTrait
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    protected function get($abstract, $defaultConcrete = null)
    {
        return getAbstractConcrete($this->getContainer(), $abstract, $defaultConcrete);
    }

    public function getCache(): CacheInterface
    {
        return $this->getContainer()->get(CacheInterface::class);
    }

    public function getLogger(): LoggerInterface
    {
        return $this->getContainer()->get(LoggerInterface::class);
    }

    public function newHttpClient($cfg = []): Client
    {
        return HttpClientFactory::newHttpClient($this, $cfg);
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->get(EventDispatcherInterface::class, NullEventDispatcher::class);
    }

    public function makeEntity($class, $properties = [])
    {
        return new $class($properties);
    }

    /**
     * 如果没有config函数, 需要重写
     * @param string $name
     * @param mixed $default [optional]
     * @return mixed
     */
    public function config($name, $default = null)
    {
        return Support::config($name, $default);
    }

}