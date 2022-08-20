<?php

namespace Shershon\Common\Factory;

use Shershon\Common\Factory\Contract\FactoryInterface;
use Shershon\Common\Factory\Traits\FactoryStaticTrait;
use Shershon\Common\Factory\Traits\FactoryTrait;

class SimpleFactory implements FactoryInterface
{

    use FactoryTrait;

    use FactoryStaticTrait;
}