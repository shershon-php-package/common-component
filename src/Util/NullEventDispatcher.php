<?php


namespace Shershon\Common\Util;


use Psr\EventDispatcher\EventDispatcherInterface;

class NullEventDispatcher implements EventDispatcherInterface
{

    public function dispatch(object $event)
    {
        // do nothing
    }

}