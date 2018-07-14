<?php
/**
 * 返回结果处理
 */
class ResultHandle {
    private $_public_key;               //公钥
    private $_reponse_content;          //返回数据

    /**
     * 设置返回数据
     */
    public function setReponseContent($data) {
        if(!is_array($data)) {
            $data = json_decode($data,true);
        }
        $this->_reponse_content = $data;
    }

    /**
     * 设置公钥
     */
    public function setPublicKey ($public_key) {
        $this->_public_key = $public_key;
    }

    /**
     * 返回数据验签
     */
    public function verifySign() {
        //非正常返回 无需验签
        if($this->_reponse_content['errcode'] != 0) {
            return true;
        }
        if(!strpos($this->_public_key,'BEGIN PUBLIC KEY')) {
            $this->_public_key = $this->getPublicKeyFromX509($this->_public_key);
        }

        $arg = $this->createLinkStr($this->_reponse_content['data']);
        //根据具体签名方式进行验签 此处默认为RSA
        $sign_res = $this->verifyRsaSign($arg,$this->_reponse_content['sign'],$this->_public_key);
        return $sign_res;
    }

    public function getReponseContent() {
        if(!is_array($this->_reponse_content)) {
            $this->_reponse_content = json_decode($this->_reponse_content,true);
        }

        return $this->_reponse_content;
    }


    /**
     * 回调验签
     */
    public function setNotify() {
        if(!strpos($this->_public_key,'BEGIN PUBLIC KEY')) {
            $this->_public_key = $this->getPublicKeyFromX509($this->_public_key);
        }

        $arg = $this->createLinkStr($this->_reponse_content);
        //根据具体签名方式进行验签 此处默认为RSA
        $sign_res = $this->verifyRsaSign($arg,$this->_reponse_content['sign'],$this->_public_key);
        return $sign_res;
    }


    /**
     * 密钥509格式
     */
    private function getPublicKeyFromX509($certificate)
    {
        $publicKeyString = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($certificate, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        return $publicKeyString;
    }

    /**
     * 构造待签名的明文
     */
    private function createLinkStr($data) {
        ksort($data);
        $arg = '';
        foreach($data as $key => $val) {
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
     * rsa验签
     */
    private function verifyRsaSign($arg,$sign,$merchant_public_key) {
        try {
            $res = openssl_get_publickey($merchant_public_key);
            $result = (bool)openssl_verify($arg, base64_decode($sign), $res);
            openssl_free_key($res);
            return $result;
        }catch (\Exception $e) {
            return false;
        }
    }

    /**
     * rsa2验签
     */
    private function verifyRsa2Sign($arg,$sign,$merchant_public_key) {
        try {
            $res = openssl_get_publickey($merchant_public_key);

            $result = (bool)openssl_verify($arg, base64_decode($sign), $res,OPENSSL_ALGO_SHA256);
            openssl_free_key($res);
            return $result;
        }catch (\Exception $e) {
            return false;
        }
    }
}