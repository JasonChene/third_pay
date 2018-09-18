<?php
header('Content-Type:text/html;charset=utf8');
date_default_timezone_set('Asia/Shanghai');

$pay_url = "http://www.pipp.org.cn/payorders";
$userkey = 'bf7d65683d5720034a58eeb395c71bce2ea8f383';
// 请求数据赋值
$data = "";

$data['partner'] =  '1198'; 
$data['paymoney'] = number_format($_REQUEST['total_fee'],2,'.','');
$data['sdorderno'] = time()+mt_rand(1000,9999); 
$data['notifyurl'] = 'http://'.dirname($_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]).'/notify.php';
$data['returnurl'] = 'http://'.dirname($_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]).'/return.php';

ksort($data);
$string = '';
foreach ($data as $key => $val) {
	$string .= $key.'='.$val.'&';
}
$data['sign'] = md5($string.$userkey);
$data['paytype'] = $_REQUEST['paytype'];
$data['remark'] = '';
$data['bankcode'] = $_REQUEST['bankcode'];
$data['get_code'] = '0';

$sHtml = "<form id='youbaopaysubmit' name='youbaopaysubmit' action='".$pay_url."' method='post'  enctype='multipart/form-data'>";
foreach ($data as $key => $val) {
    $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
}
$sHtml.= "</form>";
$sHtml.= "<script>document.forms['youbaopaysubmit'].submit();</script>";
echo $sHtml;


?>