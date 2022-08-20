<?php


namespace Shershon\Common\Adapter\Laravel;

use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Shershon\Common\Util\Support;

class LaravelProvider extends ServiceProvider
{

    public function boot()
    {
        $events = app('events');
        $list   = Support::listListener();
        foreach ($list as $value) {
            list($e, $listener) = $value;
            $events->listen($e, $listener);
        }
    }

    public function register()
    {
        $list = Support::listDefaultDependent();

        $list = array_merge($list, [
            EventDispatcherInterface::class => EventDispatcher::class,
        ]);

        foreach ($list as $key => $value) {
            if (!$this->app->has($key)) {
                $this->app->singleton($key, $value);
            }
        }

        $this->app->singleton(ContainerInterface::class, function ($app) {
            return $app;
        });
    }

}