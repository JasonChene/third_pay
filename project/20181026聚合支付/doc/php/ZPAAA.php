<?php
/*
说明：
以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
该代码仅供学习和研究API接口使用，只是提供一个参考。
*/
$merId="";//商户号
$merKey="";//商户密钥

$postUrl="https://请联系商务/api/请求地址";

//明文参数设置
//==============================================
$reqdata=array(
"action"=>"Action",				//接口类型
"txnamt"=>"1100",				//交易金额以分为单位
"merid"=>$merId,                //商户号
"orderid"=>"20180101A001",     //订单号
"backurl"=>"BACKURL",			//商户系统的地址，支付结束后，通过该url通知商户交易结果
//…
//…
//…
//…这里根据接口写好传输参数
);      
//==============================================
//格式化json字符串
$jsonData=json_encode($reqdata);
//输出Base64字符串
$base64Data = base64_encode($jsonData);
//拼接待签名字符
$signData = $base64Data.$merKey;
//签名
$sign = md5($signData);
//拼接请求参数
$requestData = "req=".urlencode($Base64Data)."&sign=".$sign;
//发送请求得到结果
$result = send_post($postUrl, $requestData);
//解析数据
$jObj = json_decode($result,true);
//取去返回数据
$resp = $jObj["resp"];
//解出返回明文
$backData = base64_decode($resp);
//对backData进行处理......

/*
* 协议请求函数http头
* @param string $url 请求地址
* @param string $post_data 数据内容
* @return array 数组类型的返回结果
*/
function send_post($url,$requestData){
    $ch = curl_init();//打开
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$requestData);
    $response  = curl_exec($ch);
    curl_close($ch);//关闭
	print_r("ret:".$response);
    $result = json_decode($response,true);
    return $result;
}
?>