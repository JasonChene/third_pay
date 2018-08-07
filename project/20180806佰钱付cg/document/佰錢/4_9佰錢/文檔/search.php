<?php
header("Content-type: text/html; charset=utf-8"); 

$key = "09ecef16d58f1440933aee3c961a1067";
$datap['trxType'] = "REQ_BankOrderQuery";
$datap['r1_orderNumber'] = "032922071312160106229758";
$datap['merchantNo'] = "1216010622";
$datap['timestamp'] = date("YmdHis");
$sign_str = "#REQ_BankOrderQuery#032922071312160106229758#1216010622#{$datap['timestamp']}#{$key}";
$datap['sign'] = md5($sign_str);


		
$str = HttpClient::Post($datap , "http://bq.baiqianpay.com/webezf/web/?app_act=openapi/bq_pay/query");
$arr = $json = json_decode($str,true);
dump($str);
dump($arr);

class HttpClient {
    public static function Post($PostArry,$request_url){
	//echo "发送地址：",$request_url,"\n";
	$postData = $PostArry;		 
	$postDataString = http_build_query($postData);//格式化参数
        
        //die();
	$curl = curl_init(); // 启动一个CURL会话
	curl_setopt($curl, CURLOPT_URL, $request_url); // 要访问的地址
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在		
	curl_setopt($curl, CURLOPT_POST, true); // 发送一个常规的Post请求
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postDataString); // Post提交的数据包
	curl_setopt($curl, CURLOPT_TIMEOUT, 60); // 设置超时限制防止死循环返回
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
		
	$tmpInfo = curl_exec($curl); // 执行操作
	if (curl_errno($curl)) {
            $tmpInfo = curl_error($curl);//捕抓异常
	}
	curl_close($curl); // 关闭CURL会话
	return $tmpInfo; // 返回数据
    }
    
    public static function Html($Url,$PostArry){         
        if(!is_array($PostArry)){
            throw new Exception("无法识别的数据类型【PostArry】");
        }
        $FormString = "<body onLoad=\"document.actform.submit()\">正在处理请稍候.....................<form  id=\"actform\" name=\"actform\" method=\"post\" action=\"" . $Url . "\">";
        foreach($PostArry as $key => $value){
            $FormString .="<input name=\"" . $key . "\" type=\"hidden\" value='" . $value . "'>\r\n";
        }
        $FormString .="</form></body>";
        
        return $FormString;
    }
    
    
}



// 浏览器友好的变量输出
function dump($var, $echo=true,$label=null, $strict=true)
{
    $label = ($label===null) ? '' : rtrim($label) . ' ';
    if(!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = "<pre>".$label.htmlspecialchars($output,ENT_QUOTES)."</pre>";
        } else {
            $output = $label . print_r($var, true);
        }
    }else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if(!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = '<pre>'. $label. htmlspecialchars($output, ENT_QUOTES). '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}
?>