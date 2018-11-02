<?php

class payBase {

    protected $errCode = '0000';
    protected $errMsg = '';
    protected $pay_config;
    protected $values = array();
    protected $debug = false; // 是否开启调试模式
    protected $is_dev_env = false; // 默认生产环境

    function __construct($config) {
        $this->pay_config = $config;

        // 是否开启调试模式
        if (array_key_exists('debug', $config)) {
            $this->debug = $config['debug'];
        }

        // 环境
        if (array_key_exists('env', $config) && $config['env'] == 'dev') {
            $this->is_dev_env = true;
        }
    }

    /**
     * 生成签名 如果value为空则该key=value不参与签名 不参与签名
     * @param
     * @return 签名
     */
    public function makeSign($arrdata = array()) {
        // 签名步骤一：按字典序排序参数
        ksort($arrdata);

        $string = self::toUrlParams($arrdata);
        //echo '<pre>本地格式化串：'.$string.'</pre>';

        // 签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $this->pay_config['mercKey'];

        // 签名步骤三：MD5加密
        $string = md5($string);

        $this->values['sign'] = $string;

        return $string;
    }

    /**
     * 验证签名
     * @param
     * @return 签名
     */
    public function checkSign($arrdata = array()) {
        // 本地签名
        $sign = $this->makeSign($arrdata);

        // 对比服务端和本地签名
        if ($arrdata['sign'] == $sign) {
            return true;
        }
        return false;
    }

    public function getErrCode() {
        return $this->errCode;
    }

    public function getErrMsg() {
        return $this->errMsg;
    }

    /**
     * 写入日志
     */
    public function logger($summary = '') {
        if (!$this->debug) return;
//        echo getcwd() . "<br/>";
//        echo dirname(__FILE__);

        $max_size = 10000;
        $log_filename = dirname(__FILE__) . "/pay-log-" . date('Y-m-d') . ".txt";
        if (file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)) {
            unlink($log_filename);
        }
        file_put_contents($log_filename, date('Y-m-d H:i:s') . " " . $summary . "\r\n\r\n", FILE_APPEND);
    }


    /**
     * 格式化参数格式化成url参数 本函数会剔除sign变量
     */
    public static function toUrlParams($values = array()) {
        $buff = "";
        if (empty($values)) {
            return '';
        }
        foreach ($values as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }


    /**
     * 生成随机字串
     * @param number $length
     *            长度，默认为16，最长为32字节
     * @return string
     */
    public static function makeRandStr($length = 32) {
        // 密码字符集，可任意添加你需要的字符
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }

    /**
     * 生成JSON，保留汉字
     * @param type $array
     * @return type
     */
    public static function jsonEncode($array) {
        $str = json_encode($array);
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', create_function('$matches', 'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'), $str);
    }

    /**
     * 以post方式提交xml到对应的接口url
     * @param type $url
     * @param type $param
     * @param int $second 设置请求超时时间
     * @param boolean $post_file 是否文件上传
     * @return mixed
     */
    public static function httpPost($url, $param, $second = 30, $post_file = false) {
        $oCurl = curl_init();
        curl_setopt($oCurl, CURLOPT_TIMEOUT, $second);
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1); // 启用更高级的ssl
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach ($param as $key => $val) {
                $aPOST[] = $key . "=" . urlencode($val);
            }
            $strPOST = join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);

        // 因为php版本的原因，上传素材一直保错。php的curl的curl_setopt 函数存在版本差异
        // php5.5已经把通过@加文件路径上传文件的方式给放入到Deprecated中了。php5.6默认是不支持这种方式了
        //curl_setopt($oCurl, CURLOPT_SAFE_UPLOAD, FALSE);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);

        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }
}