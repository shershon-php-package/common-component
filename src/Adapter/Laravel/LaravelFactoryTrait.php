<?php

namespace Shershon\Common\Adapter\Laravel;

use Psr\SimpleCache\CacheInterface;
use Shershon\Common\Factory\Traits\FactoryTrait;

trait LaravelFactoryTrait
{

    use FactoryTrait;

    public function getCache(): CacheInterface
    {
        return $this->getContainer()->get('cache')->driver();
    }

}