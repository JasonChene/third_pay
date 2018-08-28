<?php
/**
 * 公共函数
 */

/**
 * 测试银联支付
 * @param  [type] $data
 * @return [type] 
 */
function UnionPay($data){
	date_default_timezone_set("Asia/Shanghai");
    $inst_code = INSTCODE;
    $cert_id = CERTID;
    $url = UNIONPAY;
    
    $transAmt = intval($data['transAmt']);
    $orderNo = intval($data['orderNo']);
    //支付订单相关参数
    $param['transType'] = 1001;                 //交易类型
    $param['instCode'] = $inst_code;            //机构号
    $param['certType'] = 0;                     //证件类型
    $param['certId'] = $cert_id;                //证件号码
    $param['transAmt'] = $transAmt;             //金额
    $param['goodsDesc'] = "test";               //购物明细
    $param['transDate'] = date("Ymd", time());  //交易日期
    $param['orderNo'] = $orderNo;               //订单号
    
    $param['backUrl'] = BACKURL;                //后台通知URL
    $param['orderStatus'] = 0;  

    //对数据按字典序排列
    uksort($param, function ($a, $b) {
        return strcasecmp($a, $b);
    });
// var_dump($param);
    $sign = getSign($param);
    // $sign = bin2hex($sign);
    $param["sign"] = $sign;
// var_dump($param);
    
    //后续添加方法
    $result = request_post($url,$param);
    $result = json_decode($result);

    //记录返回结果
    recordReturnLog(var_export($result, true), 1);
    var_dump($result);

}

/**
 * 测试扫码支付
 * @param  [type] $data
 * @return [type] 
 */
function ScavengingPay($data){
	date_default_timezone_set("Asia/Shanghai");
    $inst_code = INSTCODE;
    $cert_id = CERTID;
    $url = SCAVENGING;
    
    $transAmt = intval($data['transAmt']);
    $orderNo = intval($data['orderNo']);
    //支付订单相关参数
    $param['transType'] = 1007;                 //交易类型
    $param['instCode'] = $inst_code;            //机构号
    $param['certType'] = 0;                     //证件类型
    $param['certId'] = $cert_id;                //证件号码
    $param['transAmt'] = $transAmt;             //金额
    $param['payType'] = '08';                   //支付类型 03-支付宝 04-微信 05-QQ钱包扫码 06-京东扫码 07-京东钱包 08-银联钱包
    $param['goodsDesc'] = "test";               //购物明细
    $param['transDate'] = date("Ymd", time());  //交易日期
    $param['orderNo'] = $orderNo;               //订单号
    
    $param['backUrl'] = BACKURL;                //后台通知URL

    //对数据按字典序排列
    uksort($param, function ($a, $b) {
        return strcasecmp($a, $b);
    });

    $sign = getSign($param);
    $param["sign"] = $sign;
    //后续添加方法
    $result = request_post($url,$param);
    $result = json_decode($result);

    //记录返回结果
    recordReturnLog(var_export($result, true), 1);
    var_dump($result);

}

/**
 * 测试订单查询
 * @param  [type] $data
 * @return [type] 
 */
function QueryOrder($data){
	date_default_timezone_set("Asia/Shanghai");
    $inst_code = INSTCODE;
    $cert_id = CERTID;
    $url = QueryOrder;
    
    $transDate = trim($data['transDate']);
    $orderNo = intval($data['orderNo']);
    //支付订单相关参数
    $param['transType'] = 2000;                 //交易类型
    $param['instCode'] = $inst_code;            //机构号
    $param['certType'] = 0;                     //证件类型
    $param['certId'] = $cert_id;                //证件号码
    $param['transDate'] = $transDate;           //原订单交易日期
    $param['orderNo'] = $orderNo;               //原订单号

    //对数据按字典序排列
    uksort($param, function ($a, $b) {
        return strcasecmp($a, $b);
    });

    $sign = getSign($param);
    $param["sign"] = $sign;
    //后续添加方法
    // var_dump($url);exit;
    $result = request_post($url,$param);
    $result = json_decode($result);

    //记录返回结果
    recordReturnLog(var_export($result, true), 1);
    var_dump($result);

}

/**
 * 测试代付
 * @param  [type] $data
 * @return [type] 
 */
function Substitute($data){
	date_default_timezone_set("Asia/Shanghai");
    $inst_code = INSTCODE;
    $cert_id = CERTID;
    $url = SCAVENGING;
    
    $transAmt = intval($data['transAmt']);
    $orderNo = intval($data['orderNo']);
    //支付订单相关参数
    $param['transType'] = 1009;                 //交易类型
    $param['instCode'] = $inst_code;            //机构号
    $param['certType'] = 0;                     //证件类型
    $param['certId'] = $cert_id;                //证件号码
    $param['transAmt'] = $transAmt;             //金额
    $param['transDate'] = date("Ymd", time());  //交易日期
    $param['orderNo'] = $orderNo;               //订单号
    $param['accountId'] = "6230111111111";      //入账银行卡号
    $param['accountName'] = "张三";             //入账银行卡户名
    $param['bankCode'] = "";                    //入账银行总行行号

    //对数据按字典序排列
    uksort($param, function ($a, $b) {
        return strcasecmp($a, $b);
    });

    $sign = getSign($param);
    $param["sign"] = $sign;

    //后续添加方法
    $result = request_post($url,$param);
    $result = json_decode($result);

    //记录返回结果
    recordReturnLog(var_export($result, true), 1);
    var_dump($result);

}

/**
 * 测试回调
 * @param  [type] $str
 * @return [type] 
 */
function back($str){
	$result = json_decode($str);
	if(!$result or empty($result->ret_code) ){
		return false;    //失败
	}
	if ($result->ret_code!="0000"){
		return false;
	}
	
	$orderStatus = $result->orderStatus;
	var_dump($orderStatus);

}


	/**
	 * 签名加密
	 * @param  [type] $param
	 * @return [type] 
	 */
	function getSign($param){
		$public_key = PUBLICKEY;

		$sign = "";
		$first = true;
	    foreach ($param as $key => $value) {
	    	if(trim($value) != ""){
	    		$key =trim($key);
		        $value =trim($value);
		        $temp = $key."=".$value;
		        if ($first) {
		            $sign .= $temp;
		            $first = false;
		        } else {
		            $sign.="&".$temp;
		        }
	    	}
	    }
	    $sign .= "&key=".$public_key;
	    $sign = MD5($sign);
	    $sign = strtoupper($sign);
	    return $sign;
	}

	/**
     * 模拟post进行url请求
     * @param string $url
     * @param string $param
     */
    function request_post($url = '', $param = array()) {
        if (empty($url) || empty($param)) {
            return false;
        }

        $header = array();
		$header[] = 'Accept:application/json';
		$header[] = 'Content-Type:application/json;charset=utf-8';
		$header[] = 'Cache-Control: no-cache';
		$header[] = 'Pragma: no-cache';

        $postUrl = $url;
        $curlPost = $param;
        $curlHandle = curl_init();//初始化curl
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
	    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
        curl_setopt($curlHandle, CURLOPT_URL,$postUrl);//抓取指定网页
        // curl_setopt($curlHandle, CURLOPT_HEADER, 0);//设置header
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $header); 
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($curlHandle, CURLOPT_POST, 1);//post提交方式
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, json_encode($curlPost));
        $result = curl_exec($curlHandle);//运行curl

        if($result === false){  //curl出错的情况
	        //记录返回结果
	        recordReturnLog(var_export(curl_error($curlHandle), true), 1);
	    }

        curl_close($curlHandle);
        
        return $result;
    }


	/**
	* 支付日志
	*
	* @param  string $info 写入的内容
	* @param  int    $type 1请求日志 2 回调日志
	*
	* @return boolean 成功或失败
	*/

	function recordReturnLog($info, $type=1) {
	    
	    $dirName = dirname(__FILE__) . '/../log/';
	    if (!file_exists($dirName)) {
	        mkdir($dirName, 0777, true);
	    }
	    
	    $ip = $_SERVER["REMOTE_ADDR"];
	    
	    if ($type==1) {
	        $filename = date("Y-m-d", time()) . 'request.txt';  //请求接口日志
	    } else {
	        $filename = date("Y-m-d", time()) . 'back.txt';     //回调记录日志
	    }
	    
	    $file = $dirName . $filename;
	    file_put_contents($file, '时间:'.date("Y-m-d H:i:s", time()).'来源ip:'.$ip.'内容:'.$info. "\r\n", FILE_APPEND | LOCK_EX);
	    
	}

?>