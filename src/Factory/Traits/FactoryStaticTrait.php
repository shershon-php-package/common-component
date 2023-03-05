<?php


namespace Shershon\Common\Factory\Traits;


use Shershon\Common\Util\Support;

trait FactoryStaticTrait
{

    private static $instance;

    /**
     * @param static|null $instance
     * @return static|null
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function instance($instance = null)
    {
        if (!is_null($instance)) {
            self::$instance = $instance;
        }
        if (is_null(self::$instance)) {
            $container = Support::container();
            empty($container) or self::$instance = $container->get(static::class);
        }
        return self::$instance;
    }


}