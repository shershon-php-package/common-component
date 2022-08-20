<?php


namespace Shershon\Common\Util;


class Str
{

    /**
     * 下划线转驼峰
     * @param string $unCamelizeWords
     * @param string $separator
     * @return string
     */
    public static function toCamelCase($unCamelizeWords, $separator = '_')
    {
        $unCamelizeWords = $separator . str_replace($separator, " ", strtolower($unCamelizeWords));
        return ltrim(str_replace(" ", "", ucwords($unCamelizeWords)), $separator);
    }

    /**
     * 驼峰转下划线
     * @param $camelCaps
     * @param string $separator
     * @return string
     */
    public static function toUnderScore($camelCaps, $separator = '_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }
}