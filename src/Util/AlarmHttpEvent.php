<?php


namespace Shershon\Common\Util;


use Throwable;

class AlarmHttpEvent extends AlarmMessageEvent
{

    public function __construct($method, $url, $option, $resp, Throwable $throwable)
    {
        $host = parse_url($url, PHP_URL_HOST);
        parent::__construct(
            sprintf("%s:%s FAILURE", $method, $host),
            sprintf("请求地址: %s, 响应: %s, 异常: %s", $url, $resp ?? json_encode($resp), empty($throwable) ? "[{$throwable->getCode()}]{$throwable->getMessage()}" : ''),
            json_encode($option)
        );
    }

}