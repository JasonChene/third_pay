<?php

class CloudPay
{
    const KEY = "22323gdhsgadhguwhhsbdwjel"; //key
    const MERCHANT_NO = "8023506000110"; //商户号
    const HOST = "http://cpqa.world-is-smart.com/";

    private $commonParams = [
        'version' => "1.0",
        'merchant_no' => self::MERCHANT_NO,
        'sign_type' => 'MD5',
    ];

    public function __construct()
    {

    }

    //统一下单接口
    public function orders($params){
        return $this->request(self::HOST ."/api/pay/unifiedorder",$params );

    }

    //统一代付接口
    public function withdraw($params){
        return $this->request( self::HOST . "/api/pay/unifiedpayorder", $params);
    }

    //代付查询接口
    public function withdrawQuery($params){
        return $this->request( self::HOST . "/api/pay/payorderquery", $params);
    }

    //代付备用金查询接口
    public function account($params){
        return $this->request( self::HOST . "/api/pay/useraccountquery", $params);
    }

    protected function request($url, Array $params){

        $params = array_merge($params, $this->commonParams);
        $strParams = $this->paramsToString($params);

        $params['sign'] = $this->getSign($strParams);

        return json_decode($this->curl($url, $params), true);
    }

    public function sign($params){
        $params = array_merge($params, $this->commonParams);
        $strParams = $this->paramsToString($params);

        return $this->getSign($strParams);

    }


    protected function paramsToString($params)
    {
        ksort($params);

        $buff = "";
        foreach ($params as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    protected function curl($url, $params='')
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        if (!empty($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        //运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    protected function getSign($params){
        return strtoupper(md5($params . '&key=' . self::KEY));
    }

    public function checkCallback(Array $params)
    {
        $sign1 = $params['sign'];
        unset($params['sign']);

        $strParams = $this->paramsToString($params);
        $sign2 = $this->getSign($strParams);

        if ($sign1 === $sign2) {
            return true;
        } else {
            return false;
        }
    }
}