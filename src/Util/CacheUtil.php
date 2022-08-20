<?php


namespace Shershon\Common\Util;


use Psr\SimpleCache\CacheInterface;

class CacheUtil
{

    /**
     * @param CacheInterface $cache
     * @param string $key
     * @param $value
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function getOrSet(CacheInterface $cache, string $key, $value)
    {
        if ($cache->has($key)) {
            return $cache->get($key);
        } else {
            $r = value($value);
            $cache->set($key, $r);
            return $r;
        }
    }

}