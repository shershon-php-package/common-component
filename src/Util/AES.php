<?php

declare(strict_types=1);

namespace Shershon\Common\Util;

class AES
{
    /**
     * AES加密
     * @param string $str 加密串
     * @param string $key key
     * @return string
     */
    public static function aesEncode($str, $key = "011ec47c909e20f9efaab31bfb156b31")
    {
        // openssl_encrypt 加密不同Mcrypt，对秘钥长度要求，超出16加密结果不变
        $data = openssl_encrypt($str, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
        return strtolower(bin2hex($data));
    }

    /**
     * AES解密
     * @param string $str 解密串
     * @param string $key key
     * @return false|string
     */
    public static function aesDecode($str, $key = "011ec47c909e20f9efaab31bfb156b31")
    {
        return openssl_decrypt(hex2bin($str), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    }

}