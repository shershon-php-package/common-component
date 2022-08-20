<?php


namespace Shershon\Common\Adapter\ThinkPHP;

use think\Event;
use Shershon\Common\Util\Support;

class ThinkPHPSubscribe
{
    public function subscribe(Event $event)
    {
        $list = Support::listListener();
        foreach ($list as $value) {
            list($e, $listener) = $event;
            $event->listen($e, $listener);
        }
    }
}