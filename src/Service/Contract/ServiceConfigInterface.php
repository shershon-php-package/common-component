<?php

namespace Shershon\Common\Service\Contract;

interface ServiceConfigInterface
{

    /**
     * 请求失败是否抛出异常
     * @return bool
     */
    public function isThrowable(): bool;

    /**
     * 返回值是数组还是对象，true为数组
     * @return bool
     */
    public function isAssoc(): bool;

    /**
     * 是否启用cache
     * @return bool
     */
    public function isCached(): bool;

}