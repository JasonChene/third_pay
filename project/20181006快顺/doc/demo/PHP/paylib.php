<?php
/**
 * Created by PhpStorm.
 * User: 陈远
 * Date: 2018/9/14
 * Time: 16:14
 */

class paylib
{

    private $token;
    private $data;
    private $charset = "utf8";
    private $url="http://120.78.145.248/";
    function __construct(){
        $this->data['version'] = '1.0';
        $this->data['paytype'] = 'alipay';
    }

    public function SetCustomerid($id){
        if(is_numeric($id)){
            $this->data['customerid']=$id;
        }
    }

    public function SetUrl($u){
        $this->url = $u;
    }

    public function SetToken($key){
        $this->token = $key;
    }

    public function SetNotifyUrl($url){
        $this->data['notifyurl'] = $url;
    }

    public function SetTotalFee($total_fee){
        if(is_numeric($total_fee)){
            $this->data['total_fee'] = number_format($total_fee,2);
        }
    }
    public function SetRemark($body){
        $this->data['remark'] = $body;
    }

    public function SetPaytype($type){
        $this->data['paytype'] = $type;
    }

    public function SetSdorderno($no){
        $this->data['sdorderno'] = $no;
    }

    public function dopay(){
        $this->data['sign'] = $this->sign();
        $res = post_curls($this->url.'apisubmit',$this->data);
        return $res;
    }
    private function sign(){
        $str = 'version=' . $this->data['version'] . '&customerid=' . $this->data['customerid'] . '&total_fee=' .  $this->data['total_fee'] . '&sdorderno=' . $this->data['sdorderno'] . '&notifyurl=' . $this->data['notifyurl'] . '&' . $this->token;
        return md5($str);
    }
    /**获取签名文本 待更改
     **/
    private function getSignContent($params)
    {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, $this->charset);
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }
    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    private function checkEmpty($value)
    {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }

    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    private function characet($data, $targetCharset)
    {
        if (!empty($data)) {
            $fileType = $this->charset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }
        return $data;
    }

    private function post_curls($url, $post)
    {
        $curl = @curl_init(); // 启动一个CURL会话
        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        }
        @curl_setopt($curl, CURLOPT_URL, $url);
        @curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        @curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        @curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        @curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        @curl_setopt($curl, CURLOPT_POST, 1);
        @curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        @curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        @curl_setopt($curl, CURLOPT_HEADER, 0);
        @curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = @curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Errno' . curl_error($curl);
        }
        curl_close($curl);
        return $res;
    }
}