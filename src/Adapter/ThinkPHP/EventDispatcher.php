<?php


namespace Shershon\Common\Adapter\ThinkPHP;

use think\facade\Event;
use Psr\EventDispatcher\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
{

    public function dispatch(object $event)
    {
        if (function_exists('event')) {
            event($event);
        }
    }

    protected function getParent(object $event)
    {
        $listParent = array_merge(
            [get_class($event)],
            class_parents($event) ?? [],
            class_implements($event) ?? []
        );

    }

}