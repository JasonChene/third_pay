<?php

class Xcurl
{

    private $curl;

    public $lastUrl = "";

    public $cookie="";

    public $lastContent = '';

    public function __construct()
    {
        $this->curl = curl_init();
        $this->setOpt(CURLOPT_TIMEOUT, 10);
        $this->setOpt(CURLOPT_RETURNTRANSFER, true); // 返回结果复制给变量，而不是直接输出到页面
        $this->setOpt(CURLOPT_FOLLOWLOCATION, 1); // 是否允许重定位
        //不对证书校验
        curl_setopt($this->curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($this->curl,CURLOPT_SSL_VERIFYHOST,false);
    }

    public function get($url)
    {
        $this->setOpt(CURLOPT_POST, false);
        return $this->exec($url, null);
    }

    public function post($url, $data = null)
    {
        $this->setOpt(CURLOPT_POST, true);
        return $this->exec($url, $data);
    }

    private function exec($url, $data = null)
    {
        if (! $url) {
            return null;
        }
        $lastUrl = $url;
        if ($data) {
            if (is_array($data)) {
                // 数组
                $this->setOpt(CURLOPT_POSTFIELDS, http_build_query($data));
            } else {
                // 字符串或者xml字符串，xml中的url需要编码
                $this->setOpt(CURLOPT_POSTFIELDS, $data);
            }
        }
        $this->setOpt(CURLOPT_URL, $url);
        $result = curl_exec($this->curl);
        $this->lastContent = $result;
        return $result;
    }

    public function setOpt($name, $key)
    {
        curl_setopt($this->curl, $name, $key);
    }

    /**
     * 返回结果复制给变量，而不是直接输出到页面
     *
     * @param unknown $true            
     */
    public function setReturnTransfer($true = 1)
    {
        $this->setOpt(CURLOPT_RETURNTRANSFER, $true);
    }

    /**
     * 是否允许重定位
     * 
     * @param unknown $true            
     */
    public function setFollowLocation($true = 1)
    {
        $this->setOpt(CURLOPT_FOLLOWLOCATION, $true); // 是否允许重定位
    }

    public function setTimeout($second){
        //秒
        $this->setOpt(CURLOPT_TIMEOUT,$second); 
    }

    /**
     * 是否允许输出响应头信息
     * 
     * @param number $true            
     */
    public function setAllowEchoHeader($true = 1)
    {
        $this->setOpt(CURLOPT_HEADER, $true);
    }
    public function setAllowRequestHeader($true = 1)
    {
        $this->setOpt(CURLINFO_HEADER_OUT, $true);    }

    /**
     * 获取请求头信息，需要先设置setAllowRequestHeader(1)
     */
    public function getRequestHeader(){
        return (curl_getinfo($this->curl,CURLINFO_HEADER_OUT));
    }

    public function setCookie($cookie)
    {
        $this->setOpt(CURLOPT_COOKIE, $cookie);
    }

    /**
     * 设置头部信息
     * -1清空
     * 
     * @param string|array $header
     *            请求头部信息；
     *            数组格式：
     *            $headers['Cache-Control'] = 'no-cache';
     *            $headers['Pragma'] = 'no-cache';
     *            $headers['CLIENT-IP'] = '202.103.229.40';
     *            $headers['X-FORWARDED-FOR'] = '202.103.229.40';
     *            $headers['REFERER'] = 'www.xxx.com';
     *            $headers['User-Agent'] ="Mozilla/5.0 (Linux; Android 4.2.2; GT-I9505 Build/JDQ39) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.59 Mobile Safari/537.36";
     *            
     */
    public function setHeader($header)
    {

        if (is_array($header)) {
            $headerArr = array();
            foreach ($header as $n => $v) {
                $headerArr[] = $n . ':' . $v;
            }
            
            $this->setOpt(CURLOPT_HTTPHEADER, $headerArr);
        } else 
            if ($header == - 1) {
                $this->setOpt(CURLOPT_HTTPHEADER, array());
            }
    }

    /**
     * setHeader 优先级高于 setReferer
     * 
     * @param unknown $url            
     */
    public function setReferer($url)
    {
        $this->setOpt(CURLOPT_REFERER, $url);
    }

    public function getInfo()
    {
        return curl_getinfo($this->curl);
    }

    /**
     * 最后访问的url
     */
    public function getUrl()
    {
        return curl_getinfo($this->curl)['url'];
    }

    /**
     * 获取跳转链接，如果有跳转的话。
     */
    public function getRedirect_url()
    {
        return curl_getinfo($this->curl)['redirect_url'];
    }

    public function getHttp_code()
    {
        return curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
    }

    public function getLastError()
    {
        return curl_error($this->curl);
    }

    public function close()
    {
        curl_close($this->curl);
    }

    /**
     * 自动关闭链接
     */
    public function __destruct()
    {
        $this->close();
    }

    public function parseCookie($content,$session_str='JSESSIONID'){
        if (empty($content)){
            $content = $this->lastContent;
        }
        $arr = array();
        preg_match_all('/Set-Cookie:[\s]?(.[^\r\n;]*)[;]?/i', $content, $arr);
        $cookies = "";
        if ($arr[1]) {
            foreach ($arr[1] as $value) {
                if (stripos($value, $session_str.'=') === false) {
                    continue;
                }
                $cookies .= $value . ';';
            }
        }
        $this->cookie = $cookies;
        return $cookies;
    }   

    public function  getCookie(){
        return $this->cookie;
    }


}

?>