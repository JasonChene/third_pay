<?php
class Util
{
    /*
     * 生成随机字符串
     */
    public static function createStr()
    {
        $nstr = 'WERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm';
        $nonce_str = substr(str_shuffle($nstr),0,32);
        return $nonce_str;
    }

    /**
     * 生成签名
     * @param $data
     * @param $app_secret
     * @return string
     */
    public static function createSign($data, $app_secret)
    {
        $sign = MD5('app_id='.$data['app_id'].'&nonce_str='.$data['nonce_str'].'&out_trade_no='.$data['out_trade_no'].'&sign_type='.$data['sign_type'].'&total_fee='.$data['total_fee'].'&version='.$data['version'].'&key='.$app_secret);
        return strtoupper($sign);
    }

    /**
     * post请求发送json数据
     * @param $url    string
     * @param $data   array
     * @return mixed
     */
    public static function postJson($url, $data)
    {
        $data = json_encode($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8', 'Content-Length:' . strlen($data)]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        return $res;
    }
}