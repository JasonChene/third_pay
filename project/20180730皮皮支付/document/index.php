<?php
/**
 * 支付提交接口
 */
error_reporting(0);
header("Content-type: text/html; charset=utf-8");
$pay_memberid = "10003";   //商户ID
// $pay_orderid = $_POST["orderid"];    //订单号
// $pay_amount =  $_POST["amount"];    //交易金额
// $pay_bankcode = $_POST["channel"];   //银行编码
// $return_type = $_POST['return_type'];
// if(empty($pay_memberid)||empty($pay_amount)||empty($pay_bankcode)){
//     die("信息不完整！");
// }
$pay_orderid = date('Y-m-d H:i:s');    //订单号
$pay_amount =  1.00;    //交易金额
$pay_bankcode = 902;   //银行编码
$return_type = 0;

$pay_applydate = date("Y-m-d H:i:s");  //订单时间
$pay_notifyurl = "http://" . 'www.kzqpay.com' . "/demo/server.php";   //服务端返回地址
$pay_callbackurl = "http://" . 'www.kzqpay.com' . "/demo/page.php";  //页面跳转返回地址
$Md5key = "04zzl7ayjedvevh0p4voxcepkbd38fnx";   //密钥

//扫码
$native = array(
    "pay_memberid" => $pay_memberid,
    "pay_orderid" => $pay_orderid,
    "pay_amount" => $pay_amount,
    "pay_applydate" => $pay_applydate,
    "pay_bankcode" => $pay_bankcode,
    "pay_notifyurl" => $pay_notifyurl,
    "pay_callbackurl" => $pay_callbackurl,
);
ksort($native);
$md5str = "";
foreach ($native as $key => $val) {
    $md5str = $md5str . $key . "=" . $val . "&";
}
//echo($md5str . "key=" . $Md5key);
$sign = strtoupper(md5($md5str . "key=" . $Md5key));
$native["pay_md5sign"] = $sign;
$native['pay_attach'] = "1234|456";
$native['pay_productname'] ='dev';
$native["return_type"] = $return_type;

//api接口提交
$url = "http://" . 'www.kzqpay.com' . "/Pay_Index.html";   //提交地址
$data = http_build_query($native);
list($returnCode, $returnContent) = curl($url, $data);
echo $returnContent;

function curl($url, $data){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/x-www-form-urlencoded; charset=utf-8"));
    ob_start();
    curl_exec($ch);
    $returnContent = ob_get_contents();
    ob_end_clean();
    $returnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    return [$returnCode, $returnContent];
}
