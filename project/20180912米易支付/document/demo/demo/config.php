<?php
class Config {
	//商户ID
	public $appId;
	//秘钥
	public $secret;
	//异步回调通知地址
 	private $notifyurl;
 	//同步回调跳转地址
    private $returnurl;
	//网关
    private $gatewayUrl = "http://miyipay.com/gateway";
    // 表单提交字符集编码
    public $postCharset = "UTF-8";
    private $fileCharset = "UTF-8";
    //返回数据格式
    private $format = "json";
    //api版本
    private $apiVersion = "1.0";
    //接口名称
    public $method = '';

    //构造必须参数
    function __construct(){
        $this->appId = '10001';
        $this->secret = '3f0b5b65f15c077698227da8c2f8ca0d55e17427';
        $this->notifyurl = 'http://'.$_SERVER['HTTP_HOST'].'/demo/notify.php';
        $this->returnurl = 'http://'.$_SERVER['HTTP_HOST'].'/demo/return.php';
    }

    //开始处理
    public function startexecution($params) {
        /*组装业务参数
        *传入：表单数据
        *返回：['content' => json_encode($param)];
        */
        $param = $this->GetPayCode($params);
        //拼装最终所需参数
        $string = $this->signString($param);
        //执行curl
        return $this->curl($string);
    }

    /*组装业务参数
	*传入：表单数据
	*返回：['content' => json_encode($param)];
	*/
    protected function GetPayCode($params){
        $method = $params['pd_FrpId'];
        //共用参数
        $data = array(
            'out_trade_no'      => '2018'.time().rand(1000,9999),//生成订单号
            'total_amount'      => $params['total_amount'],//订单金额
            'order_name'        => '支付测试',//商品描述
            'spbill_create_ip'  => $_SERVER["REMOTE_ADDR"],//用户客户端IP
            'notify_url'        => $this->notifyurl,//异步回调通知地址
            'return_url'        => $this->returnurl//同步回调跳转地址
        );
        //下面开始为不同接口追加不同参数
        switch ($method) {
            case 'refund_query':
            case 'query':
                $data = '';
                $data['out_trade_no'] = $params['out_trade_no'];//订单编号
                break;
            case 'refund':
                $data = '';
                $data['out_trade_no'] = $params['out_trade_no'];//订单编号
                $data['out_refund_no'] = $params['out_refund_no'];//退款编号
                    break;
            case 'quickpay':
                $data['user_id'] = '12314564';
                break;
            case 'alipay':
                break;
			case 'alipaywap':
                break;
            case 'weixin':
                break;
	        case 'wxh5':
                break;
            case 'qqqb':
                break;
            case 'jdqb':
                break;
            case 'ylqb':
                break;
            default:
                $data['channel_type'] = '07';//渠道类型：07-互联网；08-移动端
                $data['subject'] = $data['order_name'];//订单标题
                $data['bank_code'] = $method;//根据银行卡编码获取银行渠道编号
                $data['pay_type'] = 1;//1=借记卡；2=贷记卡；3=混合通道（借/贷记卡均可使用）
                $method = 'gateway';
                break;
        }
        //设置请求接口名称
        $this->method = $method;
        //将业务参数转为JSON格式后放入数组content中并返回
        return ['content' => json_encode($data)];
    }

    //拼装最终全部参数
    protected function signString($data){
        //校验$this->postCharset是否设置
        if ($this->checkEmpty($this->postCharset)) {
            $this->postCharset = "UTF-8";
        }
        //获取appId字符串编码
        $this->fileCharset = mb_detect_encoding($this->appId, "UTF-8,GBK");
        //  如果两者编码不一致，会出现签名验签或者乱码
        if (strcasecmp($this->fileCharset, $this->postCharset)) {
            // writeLog("本地文件字符集编码与表单提交编码不一致，请务必设置成一样，属性名分别为postCharset!");
            return "文件编码：[" . $this->fileCharset . "] 与表单提交编码：[" . $this->postCharset . "]两者不一致!";
        }
        //接口版本
        $iv = $this->apiVersion;
        //组装全部参数
        $sysParams["app_id"] = $this->appId;
        $sysParams["version"] = $iv;
        $sysParams['method'] = $this->method;
        //获取业务参数
        $apiParams = $data;
        //签名
        $sysParams = array_merge($apiParams, $sysParams);//合并数组
        //加密签名时不需要sign和sign_type
        $sysParams["sign"] = $this->md5Sign($sysParams);//加密
        $sysParams["sign_type"] = "MD5";
        return $sysParams;
    }

    //执行CURL操作并返回数据
    protected function curl($postFields = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->gatewayUrl);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $postBodyString = "";
        $encodeArray = Array();
        $postMultipart = false;
        if (is_array($postFields) && 0 < count($postFields)) {
            foreach ($postFields as $k => $v) {
                if ("@" != substr($v, 0, 1)) {//判断是不是文件上传
                    $postBodyString .= "$k=" . urlencode($this->characet($v, $this->postCharset)) . "&";
                    $encodeArray[$k] = $this->characet($v, $this->postCharset);
                } else {//文件上传用multipart/form-data，否则用www-form-urlencoded
                    $postMultipart = true;
                    $encodeArray[$k] = new \CURLFile(substr($v,1));
                }
            }
            unset ($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($postMultipart) {
                curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $encodeArray);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
            }
        }
        if($postMultipart) {
            $headers = array('content-type: multipart/form-data;charset=' . $this->postCharset.';boundary='.$this->getMillisecond());
        } else {
            $headers = array('content-type: application/x-www-form-urlencoded;charset=' . $this->postCharset);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $reponse = curl_exec($ch);
        if (curl_errno($ch)) {//CURL发生错误
            return curl_error($ch);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                return 'HTTP状态码:'.$httpStatusCode;//curl目标地址无法正常访问
            }
        }
        curl_close($ch);
        return $reponse;
    }

    //MD5加密
    protected function md5Sign($params){
        return md5(static::getSignContent($params) . '&key=' . $this->secret);
    }

    //拼接加密串
    protected function getSignContent($params) {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, $this->postCharset);

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
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    protected function characet($data, $targetCharset) {
        if (!empty($data)) {
            $fileType = $this->fileCharset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset);
                // $data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }
        return $data;
    }

    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }

    /**
      * 记录日志
    */
    public function inslog($param) {
        //将参数转为json串
        $json = json_encode($param, JSON_UNESCAPED_UNICODE);
        //获取当前地址
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
        //打开日志文件，没有则创建
        $fietime = date('Y') . date('m') . date('d').".txt";
        $myfile = fopen($fietime, "a+") or die("Unable to open file!");
        //拼接地址和参数
        $txt = $url.$json."\n";
        //写入文件
        fwrite($myfile, $txt);
        //关闭
        fclose($myfile);
    } 

    //回调验签
    public function requestSignVerify($params){
        $sign = $params['sign'];
        unset($params['sign']);
        unset($params['sign_type']);
        return $sign == $this->md5Sign($params);
    }
}
?>
