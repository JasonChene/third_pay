<?php
header("Content-type: text/html; charset=utf-8");

function get_ip(){
    if(isset($_SERVER)){
        if(isset($_SERVER[HTTP_X_FORWARDED_FOR])){
            $realip = $_SERVER[HTTP_X_FORWARDED_FOR];
        }elseif(isset($_SERVER[HTTP_CLIENT_IP])) {
            $realip = $_SERVER[HTTP_CLIENT_IP];
        }else{
            $realip = $_SERVER[REMOTE_ADDR];
        }
    }else{
        if(getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv( "HTTP_X_FORWARDED_FOR");
        }elseif(getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        }else{
            $realip = getenv("REMOTE_ADDR");
        }
    }

    return $realip;
}
$now = time();
$params = array();
$params['nonceStr'] = uniqid();
$params['startTime'] = date('YmdHis',$now);
$params['merchantNo'] = '2831106629208064';
$params['outOrderNo'] = $now.mt_rand(1000,9999);
$params['amount'] = 100000;
$params['client_ip'] =  get_ip();
$params['timestamp'] = $now;
$params['description'] = 'description';
$params['notifyUrl'] = 'notifyUrl';
$params['returnUrl'] = 'returnUrl';
$params['extra'] = 'extra';
$params['payType'] = 'wx_qr';
$params['key'] = '4d56b59cc3b447cb850b1b59513abf77';

?>
<form name='form1' method='post' action='cashier.php'>
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


