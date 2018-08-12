<?php

/**
 * RSA 公钥 私钥加密 解密 尝试
 */

namespace Ryanc\RSA;

class Provider
{

    private $_config;

    public function __construct($config_file)
    {
        $rsa_config = array();
        require_once($config_file); //配置文件
        if (empty($rsa_config['private_key']) && empty($rsa_config['public_key'])) {
            throw new Exception('请配置公钥或私钥参数');
        }
        $this->_config = $rsa_config;
    }

    /**
     * 私钥加密
     * @param string $data 要加密的数据
     * @return string 加密后的字符串
     */
    public function privateKeyEncode($data)
    {
        $res = openssl_get_privatekey($this->_config['private_key']);
        $signStr = json_encode($data, JSON_UNESCAPED_SLASHES);
        printf($signStr);
        openssl_sign($signStr, $sign, $res, OPENSSL_ALGO_SHA1);
        openssl_free_key($res);
        $sign = base64_encode($sign);
        return $sign;
    }

    public function verify($data, $sign)
    {
        $objectArray = array();
        foreach($data as $key => $value) {
            if ($key != 'sign')
                $objectArray[$key] = (string)$value;
        }
        ksort($objectArray);
        $d = json_encode($objectArray, JSON_UNESCAPED_UNICODE);
        $public_key = openssl_get_publickey($this->_config['public_key']);
        $sign = base64_decode($sign);
        $result = openssl_verify($d, $sign, $public_key,OPENSSL_ALGO_SHA1);
        openssl_free_key($public_key);
        return $result;
    }

    public function request_post($url = '', $post_data = array())
    {//url为必传  如果该地址不需要参数就不传
        if (empty($url)) {
            return false;
        }

        if (!empty($post_data)) {
            $params = '';
            foreach ($post_data as $k => $v) {
                $params .= "$k=" . urlencode($v) . "&";
                // $params.= "$k=" . $v. "&" ;
            }
            $params = substr($params, 0, -1);
        }
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
        //   curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        if (!empty($post_data)) curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
    }
}
