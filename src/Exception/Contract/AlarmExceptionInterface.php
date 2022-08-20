<?php

namespace Shershon\Common\Exception\Contract;

interface AlarmExceptionInterface
{

    /**
     * @return bool
     */
    public function isAlarm();

    /**
     * @return self
     */
    public function withNoAlarm();

}