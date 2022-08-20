<?php


namespace Shershon\Common\Adapter\Hyperf;

use Hyperf\Event\Contract\ListenerInterface;
use Shershon\Common\Util\Support;

class CommonListener implements ListenerInterface
{
    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array
    {
        $list = Support::listListener();
        return array_values(array_unique(array_column($list, 0)));
    }

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     * @param object $event
     */
    public function process(object $event)
    {
        $list = Support::listListener();
        foreach ($list as $value) {
            list($e, $listener) = $value;
            if ($event instanceof $e) {
                call_user_func($listener, $event);
            }
        }
    }

}