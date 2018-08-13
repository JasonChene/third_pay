<?php
$url = 'http://pay1.527460.cn/pay';
$params = $_POST;
$arr = $params;
unset($arr['key']);
ksort($arr);
$buff = "";
foreach ($arr as $x => $x_value){
    if($x_value != '' && !is_array($x_value)){
        $buff .= "{$x}={$x_value}&";
    }
}
$buff.="key={$params['key']}";
unset($params['key']);
$params['sign'] = strtoupper(md5($buff));
//var_dump(http_build_query($params),$buff,$url);

$config = array (
    CURLOPT_HEADER => 1,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_CONNECTTIMEOUT => 7,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_SSL_VERIFYHOST => 0,
);

$ch = curl_init ( $url );
foreach ($config as $key => $val)
{
    curl_setopt($ch, $key, $val);
}
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($params));
$response = curl_exec ($ch);
curl_close ( $ch );

list($header,$body) = explode("\r\n\r\n",$response,2);

$response = @json_decode($body,true);
//var_dump($header,$body,$response);die;
$err_message = '';
$code_url = '';
if(!$response) {
    $err_message = '网关异常';
} elseif('200' != (string)$response['status']) {
    $err_message = $response['msg'];
} else {
    $code_url = $response['data']['payUrl'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>星星支付</title>
    <meta charset="utf-8">
</head>
<body>
<?php if($err_message) {?>
<?php  echo $err_message,"<br/>",'返回信息：',$body;?>
<?php } else { ?>
    <div style="text-align:center;">
        <h3>微信支付金额：¥<b><?php echo (int)$params['amount']?></b></h3>
        <div><img src="<?php echo $code_url?>" style="width:200px;height:200px;" /></div>
    </div>
<?php }?>
<div style="text-align:center;">
    <a href="index.php">返回</a>
</div>
</body>
</html>