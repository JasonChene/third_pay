<?php
include 'config.php';
/*
 * 提交查询页面 
 */

$userId = $_POST['userId'];
$orderNo = $_POST['orderNo'];
$key =$_POST['key'];

$sign_str = '';
$sign_str = $sign_str . 'orderNo=' . $orderNo;
$sign_str = $sign_str . '&userId=' . $userId;
$sign_str = $sign_str . '&key=' . $key;

$sign = md5($sign_str);
?>
<title>订单查询</title>
<h1 style="text-align: center; margin: 30px 0 5px 0;">订单查询</h1>
<div style="margin-left: 457px;">       
    <textarea  rows="10" cols="70"><?php echo '签名原始数据：' . $sign_str ?></textarea>
    </br>
    <textarea  rows="10" cols="70"><?php echo '签名加密后数据：' . $sign ?></textarea>

    <form method='post' action='<?php echo QUERY_URL; ?>' target="_blank">
        <input type='hidden' name='userId' value='<?php echo $userId; ?>' />        
        <input type='hidden' name='orderNo' value='<?php echo $orderNo; ?>' />
        <input type='hidden' name='sign' value='<?php echo $sign ?>' />
        <input type="submit" value="提交查询" style="width: 80px; height: 30px; margin:10px 0 0 215px;">
    </form>
</div>




