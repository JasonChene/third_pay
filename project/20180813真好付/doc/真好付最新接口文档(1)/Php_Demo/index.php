<?php

/**
 * 获取客户端ip
 */
function get_client_ip()
{
    $keys = array ('CLIENT_IP', 'REMOTE_ADDR' );
    foreach ( $keys as $key )
    {
        if (isset($_SERVER[$key]))
        {
            return $_SERVER[$key];
        }
    }
    return null;
}

$now = time();
$params = array();
$params['nonceStr'] = uniqid();
$params['startTime'] = date('YmdHis',$now);
$params['merchantNo'] = '111111';
$params['outOrderNo'] = $now.mt_rand(1000,9999);
$params['amount'] = 100;
$params['client_ip'] = get_client_ip();
$params['timestamp'] = $now;
$params['description'] = 'description';
$params['extra'] = 'extra';
$params['notifyUrl'] = 'http://baidu.com/notify';
$params['payType'] = 'wx_qr';
$params['key'] = '1234567';

?>
<!DOCTYPE html>
<html>
<head>
    <title>星星支付</title>
    <meta charset="utf-8">
</head>
<body>
<form action="pay.php" method="post">
    <table>
    <?php foreach ($params as $k=>$v) {?>
        <tr>
            <td><?php echo $k;?>:</td>
            <td><input type="text" name="<?php echo $k;?>" value="<?php echo $v;?>"/></td>
        </tr>
    <?php }?>
        <tr><td></td><td><input type="submit" value="去支付"/> </td></tr>
    </table>
</form>

<form action="query.php" method="post">
    <table style="margin-top:30px;">
        <tr>
            <td>订单号:</td>
            <td><input type="text" name="outOrderNo" value=""/></td>
        </tr>
        <tr>
            <td>随机字符串:</td>
            <td><input type="text" name="nonceStr" value="<?php echo $params['nonceStr']?>"/></td>
        </tr>
        <tr>
            <td>商户号:</td>
            <td><input type="text" name="merchantNo" value="<?php echo $params['merchantNo']?>"/></td>
        </tr>
        <tr>
            <td>时间戳:</td>
            <td><input type="text" name="timestamp" value="<?php echo $params['timestamp']?>"/></td>
        </tr>
        <tr>
            <td>key:</td>
            <td><input type="text" name="key" value="<?php echo $params['key']?>"/></td>
        </tr>
        <tr><td></td><td><input type="submit" value="去查询"/></td></tr>
    </table>
</form>

</body>
</html>
