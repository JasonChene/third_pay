<?php
include_once("config.php");

//商户订单号
$out_trade_no = $_GET['out_trade_no'];
//平台交易号
$dd = $_GET['dd'];
//交易金额
$total_fee = $_GET['total_fee'];

//签名验证
$sign_md5 = md5_sign($_GET,$config['key']);

//验证签名
if($sign_md5 == $_GET['sign']){ 

    echo "支付成功！ - 定单号：".$out_trade_no;

    //这里可以对该订单进行操作

    //修改订单状态为1
    get_dingdan($out_trade_no,1);

} else {  //支付失败

    echo "支付失败！- 签名错误";
}
?>