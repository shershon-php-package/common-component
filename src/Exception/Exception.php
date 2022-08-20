<?php

namespace Shershon\Common\Exception;

use Throwable;
use Shershon\Common\Exception\Contract\AlarmExceptionInterface;

class Exception extends \Exception implements AlarmExceptionInterface
{

    use AlarmExceptionTrait;

    public function __construct($code = 0, $message = "", ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function isAlarm()
    {
        return $this->isAlarm;
    }

    public function withNoAlarm()
    {
        $clone          = clone $this;
        $clone->isAlarm = false;
        return $clone;
    }

}