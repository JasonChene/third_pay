<?php
/* *
 * 功能：支付接口调试入口页面
 * 版本：1.0
 * 修改日期：2018-06-11
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */
ini_set('display_errors','on');
error_reporting(E_ALL);
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
require_once dirname ( __FILE__ ).'/lib/PayUtils.php';
require_once dirname ( __FILE__ ).'/lib/phpqrcode/phpqrcode.php';
if (!empty($_POST['out_trade_no'])){
    //echo "<pre>";
    /*$_POST['out_trade_no']='OF'.time();
    $_POST['total_fee']='0.01';
    $_POST['currency_type']='CNY';
    $_POST['pay_id']='WECHAT_NATIVE';
    $_POST['goods_name']='充值';*/
    $pay_data=array();
    $pay_data['out_trade_no']=$_POST['out_trade_no'];//订单号
    $pay_data['total_fee']=$_POST['total_fee'];//订单金额
    $pay_data['currency_type']=$_POST['currency_type'];//货币代码
    $pay_data['pay_id']=$_POST['pay_id'];//货币代码
    $pay_data['goods_name']=$_POST['goods_name'];//商品名称
    $pay_data['order_type'] = '1';//发起类型 1网页
    $Pay=new PayUtils();
    $result=$Pay->paySubmit($pay_data);
    //print_r($result);exit;
    if($result['data']['resp_code']!='00'){
        echo $result['data']['resp_desc'];exit;
    }
    //验签
    $status=$Pay->makeNotifySign($result['data'],'pay_return');
    if(!$status){
       // echo "<br>验签失败";exit;
    }
    if($result['data']['payment'] ){
        QRcode::png($result['data']['payment'], 'pay1.png','L', 4, 2);   
        echo "<img src='pay1.png'>";
    }else{
        echo '没有支付链接';
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
	<title>支付接口</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
    * {
        padding:0;
        margin:0;
    }
    body{
        padding:30px 50px;
    }
    ul,ol{
        list-style:none;
    }
   .header{
        font-size:30px;
    }
    .main{
        margin-top:10px;
        font-size:14px;
        line-height:28px;
    }
    .main ul:after{
        content:".";
        clear:both;
        display:block;
        height:0;
        overflow:hidden;
        visibility:hidden;
    }
    .main li{
        float: left;
    }
    .main button{
        padding:2px 5px;
    }
</style>
</head>
<body>
<div class="header">
    支付下单接口
</div>
<div class="main">
    <form name="payment" action='' method=post>
        <ul>
            <li>商户订单号：</li>
            <li>
                <input id="out_trade_no" name="out_trade_no" value="OF<?php echo time();?>"/>*以OF开头
            </li>
        </ul>
        <ul>
            <li>付款金额：</li>
            <li>
                <input id="total_fee" name="total_fee" value="100"/>
            </li>
        </ul>
        <ul>
            <li>货币类型：</li>
            <li>
                <input id="currency_type" name="currency_type"  value="CNY"/>
            </li>
        </ul>
        
        <ul>
            <li>支付方式：</li>
            <li>
                <input id="pay_id" name="pay_id" value="WECHAT_NATIVE"/>
            </li>
        </ul>
        <ul>
            <li>商品名称：</li>
            <li>
                <input id="goods_name" name="goods_name" value="充值"/>
            </li>
        </ul>
        <ul>
            <li></li>
            <li>
                <button  type="submit" style"text-align:center;">确 认</button>
            </li>
        </ul>
    </form>
</div>
</body>
</html>