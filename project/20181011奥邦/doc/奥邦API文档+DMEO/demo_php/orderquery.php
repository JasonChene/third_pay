<?php
date_default_timezone_set("Asia/Shanghai");

//--------------------------------------------1、基础参数配置------------------------------------------------

const TRX_KEY = '7udoz4wv67fsht3ryg1brzesuj2grjue'; //商户后台支付key
const SECRET_KEY = '109b6f64bed6e252437e9de376ed2e7f'; //商户后台支付密钥
const API_URL = 'https://www.aobangapi.com/pay/order/query'; //查询API请求地址

//--------------------------------------------end基础参数配置------------------------------------------------

//请求参数
$params = array(
    'trx_key' => TRX_KEY,
    'request_id' => 'TS2018011421063523561',//商户支付请求订单号
);
ksort($params);
$paramStr = "";
//拼接字符串参数
while (list ($key, $val) = each($params)) {
    $paramStr.=$key . "=" . $val . "&";
}
//去掉最后一个&字符
$paramStr = substr($paramStr, 0, -1);

$preSignStr = $paramStr."&secret_key=".SECRET_KEY;  //此字串要来生成签名
$sign = strtoupper(md5($preSignStr));
$signStr = $paramStr."&sign=".$sign;  //此处不能用签名前带有secret_key的字串，secret_key不能作为参数传，只能用于生成签名
//$result  = curl_post_https(API_URL,$signStr);//以POST执行请求
$result  = curl_get_https(API_URL,$signStr);//以GET执行请求

print_r($result);
exit;

/* *******PHP CURL HTTPS POST ***********/
function curl_post_https($url,$data){ // 模拟提交数据函数
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        echo 'Errno'.curl_error($curl);//捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据，json格式
}


/****curl以GET方式请求https协议接口*///
function curl_get_https($url,$data)
{
    $url = $url.'?'.$data;
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
    $tmpInfo = curl_exec($curl);     //返回api的json对象
    //关闭URL请求
    curl_close($curl);
    return $tmpInfo;    //返回json对象

}






