<?php

use Psr\Container\ContainerInterface;

if (!function_exists("container")) {

    function container(): ?\Psr\Container\ContainerInterface
    {
        if (function_exists('app')) {
            return app();
        }
        if (class_exists("Hyperf\Utils\ApplicationContext")) {
            return Hyperf\Utils\ApplicationContext::getContainer();
        }
        return null;
    }

}

if (!function_exists("getAbstractConcrete")) {

    /**
     * 取得一个实现
     * @param ContainerInterface $container
     * @param $abstract
     * @param null $defaultConcrete
     * @return mixed|null
     */
    function getAbstractConcrete(ContainerInterface $container, $abstract, $defaultConcrete = null)
    {
        return $container->has($abstract)
            ? $container->get($abstract)
            : (is_null($defaultConcrete) ? null : $container->get($defaultConcrete));
    }
}
