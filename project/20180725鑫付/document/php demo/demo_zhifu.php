<?php  
  


function request_post($url = '', $param = '')
{
    if (empty($url) || empty($param)) {
        return false;
    }
    $postUrl = $url;
    $curlPost = $param;
    $ch = curl_init(); // 初始化curl
    curl_setopt($ch, CURLOPT_URL, $postUrl); // 抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0); // 设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1); // post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch); // 运行curl
    curl_close($ch);
    

    return $data;
}
  
$data['merchantId']="";   //商户号
$data['totalAmount']="";  //金额
$data['desc']="";
$data['corp_flow_no']=time().rand(100000000,999999999);//订单号
$data['model']="WXZF";  //1支付宝  2微信 
$data['notify_url']="";   //回调地址
$data['client_ip']="";   //ip地址

$key="";   //密钥
$data['sign']=MD5($data['merchantId']."pay".$data['totalAmount'].$data['corp_flow_no'].$key);
$res=request_post("http://域名:8081/YUMPay/doPay.do",$data);

//下面输出
$res=json_decode($res,true);
var_dump($res);