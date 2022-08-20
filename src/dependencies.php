<?php

use Shershon\Common\Factory\Contract\FactoryInterface;
use Shershon\Common\Factory\SimpleFactory;
use Shershon\Common\Http\Contract\LogFormat;
use Shershon\Common\Http\Format;
use Shershon\Common\Service\Contract\ServiceConfigInterface;
use Shershon\Common\Service\DefaultServiceConfig;
use Shershon\Common\Util\Support;

Support::setDefaultDependent(FactoryInterface::class, SimpleFactory::class);
Support::setDefaultDependent(ServiceConfigInterface::class, DefaultServiceConfig::class);
Support::setDefaultDependent(LogFormat::class, Format::class);