<?php
/*
* 第一种接入表单方式 表单页index.html
*/
$money          = trim($_POST['money']); //支付金额
$type           = 'form';  //默认为表单模式。form：表单模式；json：返回json结构

$merchantId ='6001024'; //测试商户ID  6001024
$secretKey='9AF6D9E349707FC20139B433D314AD6C'; //测试商户秘钥    请登录小财神商家后台获取最新秘钥


$paytype = $_POST['paytype'];
$timestamp      = time()*1000;//时间截
$goodsName      = '测试商品';//商品的名称，商户后台会展示该名称。
$notifyURL      = 'https://www.baidu.com/paynotify.php'; //支付成功回调地址
$returnURL      = 'https://www.baidu.com/return.php';//支付成功跳转页面
$merchantOrderId= 'test'.time();//商户自定的订单号，该订单号将后在后台展示。
$merchantUid    = 'test'.mt_rand(10000,99999);

$sign           = md5($money.'&'.$merchantId.'&'.$notifyURL.'&'.$returnURL.'&'.$merchantOrderId.'&'.$timestamp.'&'.$secretKey);
$param = array(
    'money'             => $money,
    'type'              => $type,                               //默认为表单模式。form：表单模式；json：返回json结构
    'merchantId'        => $merchantId,                         //必填。您的商户唯一标识，注册后在设置里获得
    'timestamp'         => $timestamp,                          //必填。精确到毫秒
    'goodsName'         => $goodsName,                          //选填。商品的名称，商户后台会展示该名称。
    'notifyURL'         => $notifyURL,                          //选填。支付成功后系统会对该地址发起回调，通知支付成功的消息。
    'returnURL'         => $returnURL,                          //选填。成功成功后系统会跳转页面到该地址上。
    'merchantOrderId'   => $merchantOrderId,                    //必填。商户自定的订单号，该订单号将后在后台展示。
    'merchantUid'       => $merchantUid,                        //选填。商户提交支付的用户Id，该ID会后台展示。
    'sign'              => $sign                                //必填。把参数，连Token一起，按指定的顺序。做md5-32位加密，取字符串小写。得到key。
);

$url='https://api.561581.com/api/receive?type=form';
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<form style='display: none' id='formpay' name='formpay' method='post' action='<?=$url?>'>
    <input type='text' value='<?=$money?>' name='money'             id='money' /><br />
    <input type='text' value='<?=$type?>' name='type'              id='type' /><br />
    <input type='text' value='<?=$merchantId?>' name='merchantId'        id='merchantId' /><br />
    <input type='text' value='<?=$timestamp?>' name='timestamp'         id='timestamp' /><br />
    <input type='text' value='<?=$goodsName?>' name='goodsName'         id='goodsName' /><br />
    <input type='text' value='<?=$notifyURL?>' name='notifyURL'         id='notifyURL' /><br />
    <input type='text' value='<?=$returnURL?>' name='returnURL'         id='returnURL' /><br />
    <input type='text' value='<?=$merchantOrderId?>' name='merchantOrderId'   id='merchantOrderId' /><br />
    <input type='text' value='<?=$merchantUid?>' name='merchantUid'       id='merchantUid' /><br />
    <input type='text' value='<?=$sign?>' name='sign'              id='sign' /><br />
    <input type='text' value='<?=$paytype?>' name='paytype'              id='paytype' /><br />
    <input type='submit' id='submitdemo1'>
</form>
<script>
    document.getElementById('formpay').submit();
</script>
