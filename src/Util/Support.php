<?php


namespace Shershon\Common\Util;


use Psr\Container\ContainerInterface;
use Shershon\Common\Factory\Contract\FactoryInterface;
use Shershon\Common\Factory\SimpleFactory;

/**
 * 框架支持
 * Class Support
 * @package Shershon\Common\Util
 */
class Support
{

    protected static $configEnv = [
        'app_name',
        'app_env'
    ];

    protected static $dependent = [];

    protected static $listener = [];

    public static function getFactory(): FactoryInterface
    {
        return getAbstractConcrete(static::container(), FactoryInterface::class, SimpleFactory::class);
    }

    public static function container(): ?ContainerInterface
    {
        return container();
    }

    public static function setDefaultDependent($abstract, $concrete)
    {
        self::$dependent[$abstract] = $concrete;
    }

    public static function listDefaultDependent()
    {
        return self::$dependent;
    }

    public static function listenerRegister($event, $listener)
    {
        $class = $method = "";
        if (is_array($listener) && count($listener) == 2) {
            list($class, $method) = $listener;
        }
        if (is_string($listener)) {
            $export = explode("@", $listener);
            if (count($export) == 2) {
                list($class, $method) = $export;
            } else {
                $class = $listener;
            }
        }

        if (class_exists($class)) {
            empty($method) && $method = "process";
            $listener = function ($event) use ($class, $method) {
                if (!is_object($class)) {
                    $class = Support::container()->get($class);
                }
                return call_user_func_array([$class, $method], func_get_args());
            };
        }

        self::$listener = array_merge(self::$listener, [$event, $listener]);
    }

    public static function listListener()
    {
        return self::$listener;
    }

    public static function config($name, $default = null)
    {
        if (function_exists('config')
            || function_exists(__NAMESPACE__ . '\config')) {
            if ((function_exists('env') || function_exists(__NAMESPACE__ . '\env')) && in_array(strtolower($name), static::$configEnv)) {
                return config($name, $default ?? env(strtoupper($name)));
            }
            return config($name, $default);
        }
        return $default;
    }

}