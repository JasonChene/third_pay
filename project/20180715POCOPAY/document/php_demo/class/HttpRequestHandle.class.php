<?php
/**
 * 设置请求报文参数
 */

class HttpRequestHandle {
    private $_gateway_url;          //网关地址

    private $_request_param;        //请求参数

    private $_private_key;          //商户私钥


    /**
     * 设置网关地址
     */
    public function setGatewayUrl($gateway_url) {
        $this->_gateway_url = $gateway_url;
    }

    /**
     * 设置公钥
     */
    public function setPrivateKey($private_key) {
        $this->_private_key = $private_key;
    }

    /**
     * 一次性设置参数
     */
    public function setBatchParam($param,$field = []) {
        if(!empty($field)) {
            foreach($field as $val) {
                if(isset($param[$val])) {
                    unset($param[$val]);
                }
            }
        }
        foreach($param as $k => $val) {
            if($val != '') {
                $this->_request_param[$k] = $val;
            }
        }

        $sign = $this->createSign();
        $this->_request_param['sign'] = $sign;
    }

    /**
     * 获取网关地址
     */
    public function getGatewayUrl() {
        return $this->_gateway_url;
    }

    /**
     * 获取报文
     */
    public function getRequestParam() {
        return $this->_request_param;
    }

    /**
     * 生成签名
     */
    private function createSign() {
        $arg  = $this->createLinkStr();
        $sign = $this->rsaSign($arg);

        return $sign;
    }

    /**
     * 构造待签名的明文
     */
    private function createLinkStr() {
        $arg = '';
        ksort($this->_request_param);
        foreach($this->_request_param as $key => $val) {
            if($key == 'sign' || $val == '')
                continue;

            $arg .= $key.'='.$val.'&';
        }
        //去掉最后一个&符号
        $arg = substr($arg,0,strlen($arg) - 1);
        //如果带有反斜杠 则转义
        if(get_magic_quotes_gpc()) $arg = stripslashes($arg);

        return $arg;

    }

    /**
     * RSA签名
     * @param $data 待签名数据
     * @param $private_key_path 商户私钥文件路径
     * return 签名结果
     */
    private function rsaSign($arg) {
        if(!in_array($this->_request_param['sign_type'],['RSA','RSA2'])) {
            return false;
        }
        try{
            $res = openssl_get_privatekey($this->_private_key);
            if($this->_request_param['sign_type'] == 'RSA') {
                openssl_sign($arg, $sign, $res);
            }else{
                openssl_sign($arg, $sign, $res,OPENSSL_ALGO_SHA256);
            }

            openssl_free_key($res);
            //base64编码
            $sign = base64_encode($sign);
            return $sign;
        }catch (\Exception $e) {
            return false;
        }

    }


}