<?php

namespace Shershon\Common\Service\Traits;

trait ServiceConfigTrait
{

    public function isThrowable(): bool
    {
        return false;
    }

    public function isAssoc(): bool
    {
        return true;
    }

    public function isCached(): bool
    {
        return true;
    }

}