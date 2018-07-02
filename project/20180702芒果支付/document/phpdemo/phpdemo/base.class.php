<?php
class Base {
    //超时时间
    protected $resTimeOut = 20;
    /** 
     * 发送数据
     * @param string $url - 地址
     * @param array $data - 发送的消息
     * @return bool
     */
	public function curlPost($url, $data)
    {
        try{
            $header[] = "Content-type: application/x-www-form-urlencoded;charset=UTF-8";
            $ch = curl_init ();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->resTimeOut);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->resTimeOut);     
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            $result = curl_exec($ch);
            curl_close($ch);
            return json_decode($result, true);
        }catch(\Exception $e){
            return ['code'=>'9999', 'msg'=>'接口链接失败'];
        }
    }
    /**
     * 内部验签
     * @access public
     * @param array $post  数据
     * @param string $secretKey  密钥
     * @return Driver
     */
    public function checkInSign($post, $secretKey)
    {
        return $post['sign'] == $this->makeInSign($post, $secretKey);
    }
    /**
     * 生成内部签名
     * @access public
     * @param array $post  数据
     * @param string $secretKey  密钥
     * @return Driver
     */
    public function makeInSign($post, $secretKey)
    {   
        $noarr = ['sign','code','msg'];
        ksort($post);
        $data = "";
        foreach ($post as $key => $val) {
            if ( !in_array($key, $noarr) && (!empty($val) || $val ===0 || $val ==='0') ) {
                $data .= $key . '=' . $val . '&';
            }
        }
        $data .= 'key='.$secretKey;
        return strtolower(md5($data));
    }
    public function createNo()
    {
        return date('YmdHis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}