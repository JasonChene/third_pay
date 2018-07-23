<?php

class rsa
{

    /**
     * 验证签名
     * @param string $data 数据
     * @param string $sign 签名
     * @param string $publicKey 公钥
     * @return bool
     */
    public static function verifySign($data = '', $sign = '', $publicKey = '')
    {
        if (!is_string($sign) || !is_string($sign)) {
            return false;
        }
        return (bool)openssl_verify(
            $data,
            base64_decode($sign),
            $publicKey,
            OPENSSL_ALGO_SHA256
        );
    }
}