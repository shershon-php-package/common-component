<?php


namespace Shershon\Common\Adapter\Laravel;


use Psr\EventDispatcher\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
{

    public function dispatch(object $event)
    {
        if (function_exists('event')) {
            event($event);
        }
    }

}