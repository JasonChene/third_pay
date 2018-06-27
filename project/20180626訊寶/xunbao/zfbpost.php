<?php session_start(); ?>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

if(function_exists("date_default_timezone_set"))
{
	date_default_timezone_set("Asia/Shanghai");
}

function curl_post($url,$data){ #POST访问
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec($ch);
	if (curl_errno($ch)) {
	  return curl_error($ch);
	}
	return $tmpInfo;
  }
//获取第三方的资料
$params = array(':pay_type'=>$_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'].$row['wx_returnUrl'];
$merchant_url = $row['pay_domain'].$row['wx_synUrl'];

$pay_type = $row['pay_type'];
if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}#固定参数设置
$top_uid = $_REQUEST['top_uid'];
$order_no = getOrderNo();
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');
$form_url ='http://gateway.xunbaopay9.com/chargebank.aspx';

#第三方参数设置
$data =array(
  'parter' => $pay_mid,
  'type' => "",
  'value' => $mymoney,
  'orderid' => $order_no,
  'callbackurl' =>$merchant_url,
  'hrefbackurl' => $return_url,
  'payerIp' => getClientIp(),
  'attach'=> "",
  'sign' => "",
  'agent' => "",
);

#变更参数设置
$scan = 'zfb';
$payType = $pay_type."_zfb";
$bankname = $pay_type . "->支付宝在线充值";
if (_is_mobile()) {
	$data['type'] = '931';//支付宝 H5
  } else {
	$data['type'] = '8012';//支付宝掃碼
  }
$result_insert = insert_online_order($_REQUEST['S_Name'] , $order_no , $mymoney,$bankname,$payType,$top_uid);
			
if ($result_insert == -1)
{
	echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
	exit;
}
else if ($result_insert == -2)
{
	echo "订单号已存在，请返回支付页面重新支付";
	exit;
}
	  
$md5= "parter=".$data['parter']."&type=".$data['type']."&value=".$data['value']."&orderid=".$data['orderid']."&callbackurl=".$data['callbackurl'].$pay_mkey;
$data['sign']=md5($md5);
if (_is_mobile()) {
	$res= curl_post($form_url,$data);
	$jumpurl = $res;
}else {
	#直接表單post
	$form_data = $data;
	$jumpurl = $form_url;
}
?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>支付宝网页版</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/index.css" media="all">
</head>
<body onLoad="document.ddbill.submit();">
	<form name="ddbill" id="ddbill" method="post" action="<?php echo $jumpurl?>">
	<?php
          if(isset($form_data)){
              foreach ($form_data as $arr_key => $arr_value) {
          ?>
              <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
          <?php }} ?>
		</form>
	</body>
</html>