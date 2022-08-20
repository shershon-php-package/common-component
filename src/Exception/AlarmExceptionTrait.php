<?php


namespace Shershon\Common\Exception;


trait AlarmExceptionTrait
{

    protected $isAlarm = true;

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

    public function __clone()
    {
    }
}