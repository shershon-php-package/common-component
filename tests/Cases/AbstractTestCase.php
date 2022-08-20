<?php

namespace Shershon\CommonTest\Cases;

use DI\Container;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Shershon\Common\Factory\SimpleFactory;

/**
 * Class AbstractTestCase.
 */
abstract class AbstractTestCase extends TestCase
{

    const LOGGER = false;

    public function factory()
    {
        $container = new Container();
        SimpleFactory::instance($container->get(SimpleFactory::class));
        $container->set(LoggerInterface::class, function () use ($container) {
            return $this->loggerFactory($container);
        });
        return SimpleFactory::instance();
    }

    private function loggerFactory($container)
    {
        if (self::LOGGER) {
            $logger  = new Logger("test");
            $handler = new StreamHandler("php://stdout");
            $logger->pushHandler($handler);
            return $logger;
        } else {
            return new NullLogger();
        }
    }

}
