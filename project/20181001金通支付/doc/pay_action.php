<?php
include 'config.php';
/*
 * 提交支付页面
 */

$userId = $_POST['userId'];
$orderNo = $_POST['orderNo'];
$tradeType = $_POST['tradeType'];
$payAmt = $_POST['payAmt'];
$goodsName = $_POST['goodsName'];
$returnUrl = $_POST['returnUrl'];
$notifyUrl = $_POST['notifyUrl'];
$key = $_POST['key'];

$sign_str = '';
$sign_str = $sign_str . 'notifyUrl=' . $notifyUrl;
$sign_str = $sign_str . '&orderNo=' . $orderNo;
$sign_str = $sign_str . '&payAmt=' . $payAmt;
$sign_str = $sign_str . '&returnUrl=' . $returnUrl;
$sign_str = $sign_str . '&tradeType=' . $tradeType;
$sign_str = $sign_str . '&userId=' . $userId;
$sign_str = $sign_str . '&key=' . $key;
$sign = md5($sign_str);
?>
<title>支付Demo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<h1 style="text-align: center; margin: 30px 0 5px 0;">微信支付</h1>
<div style="margin-left: 457px;">    
    <textarea rows="10" cols="70"><?php echo '签名原始数据：' . $sign_str; ?></textarea>
    </br>
    <textarea rows="10" cols="70"><?php echo '签名加密后数据：' . $sign; ?></textarea>

	//返回的界面处理参考rechargeaction.jsp,这文件是java的文件，仅供参考，请自行改成html
    <form  method='post' action='<?php echo PAY_URL; ?>' accept-charset="utf-8" onsubmit="document.charset='utf-8';">
        <input type='text' name='userId' value='<?php echo $userId; ?>' />        
        <input type='text' name='orderNo' value='<?php echo $orderNo; ?>' />
        <input type='text' name='tradeType' value='<?php echo $tradeType; ?>' />
        <input type='text' name='payAmt' value='<?php echo $payAmt; ?>' />
        <input type='text' name='goodsName' value='<?php echo $goodsName; ?>' />
        <input type='text' name='returnUrl' value='<?php echo $returnUrl; ?>' />
        <input type='text' name='notifyUrl' value='<?php echo $notifyUrl; ?>' />
        <input type='text' name='sign' value='<?php echo $sign; ?>' />
        <input type="submit" value="提交支付" style="width: 80px; height: 30px; margin:10px 0 0 215px;">
    </form>
</div>


