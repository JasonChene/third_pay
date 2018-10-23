<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");//原数据库的连接方式
// include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
	date_default_timezone_set("Asia/Shanghai");
}

#function
function curl_post($url, $data)
{ #POST访问
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec($ch);
	if (curl_errno($ch)) {
		return curl_error($ch);
	}
	return $tmpInfo;
}

function payType_bankname($scan, $pay_type)
{
	global $payType, $bankname;
	if ($scan == "wy") {
		$payType = $pay_type . "_wy";
		$bankname = $pay_type . "->网银在线充值";
	} elseif ($scan == "yl" || $scan == "ylfs") {
		$payType = $pay_type . "_yl";
		$bankname = $pay_type . "->银联钱包在线充值";
	} elseif ($scan == "qq" || $scan == "qqfs") {
		$payType = $pay_type . "_qq";
		$bankname = $pay_type . "->QQ钱包在线充值";
	} elseif ($scan == "wx" || $scan == "wxfs") {
		$payType = $pay_type . "_wx";
		$bankname = $pay_type . "->微信在线充值";
	} elseif ($scan == "zfb" || $scan == "zfbfs") {
		$payType = $pay_type . "_zfb";
		$bankname = $pay_type . "->支付宝在线充值";
	} elseif ($scan == "jd" || $scan == "jdfs") {
		$payType = $pay_type . "_jd";
		$bankname = $pay_type . "->京东钱包在线充值";
	} elseif ($scan == "ylkj") {
		$payType = $pay_type . "_ylkj";
		$bankname = $pay_type . "->银联快捷在线充值";
	} elseif ($scan == "bd" || $scan == "bdfs") {
		$payType = $pay_type . "_bd";
		$bankname = $pay_type . "->百度钱包在线充值";
	} else {
		echo ('payType_bankname出错啦！');
		exit;
	}
}

function QRcodeUrl($code)
{ #替换QRcodeUrl中&符号
	if (strstr($code, "&")) {
		$code2 = str_replace("&", "aabbcc", $code);//有&换成aabbcc
	} else {
		$code2 = $code;
	}
	return $code2;
}

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);//原数据库的连接方式
// $stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商戶私钥
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];//return跳转地址
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];//notify回传地址
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}
#固定参数设置
$top_uid = $_REQUEST['top_uid'];
$order_no = getOrderNo();
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');

#第三方参数设置
$data = array(
	"key" => $pay_mkey, //后台显示的通讯key
	"record" => $order_no, //附加信息，如：用户网站的用户名，订单号， 手机号，等唯一标识
	"money" => number_format($_REQUEST['MOAmount'], 2, '.', ''), //金额
	"refer" => $return_url, //支付成功或订单超时自动跳转的地址
	"paytype" => '', //支付类型
	"notify_url" => $merchant_url, //异步通知地址,又称回调地址
	"sign" => '', //数据签名
);

#变更参数设置
$form_url = 'http://gateway.xingbao123.com';//请求地址
if (strstr($pay_type, "QQ钱包") || strstr($pay_type, "qq钱包")) {
	$scan = 'qq';
	$data['paytype'] = '3';
} else {
	$scan = 'wx';
	$data['paytype'] = '2';
}
payType_bankname($scan, $pay_type);

#新增至资料库，確認訂單有無重複， function在 moneyfunc.php裡(非必要不更动)
$result_insert = insert_online_order($_REQUEST['S_Name'], $order_no, $mymoney, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
	echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
	exit;
} else if ($result_insert == -2) {
	echo "订单号已存在，请返回支付页面重新支付";
	exit;
}

#签名排列，可自行组字串或使用http_build_query($array)
$signtext = floatval($data['money']) . trim($data['record']) . $pay_mkey;
$sign = md5($signtext);
$data['sign'] = $sign;

// //打印
// echo '<pre>';
// echo ('<br> data = <br>');
// var_dump($data);
// echo ('<br> signtext = <br>');
// echo ($signtext);
// echo ('<br><br> row = <br>');
// var_dump($row);
// echo '</pre>';

// exit;

$jumpurl = $form_url;

#跳轉方法
?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
  <form method="post" id="frm1" action="<?php echo $form_url ?>" target="_self">
     <p>正在为您跳转中，请稍候......</p>
       <?php foreach ($data as $arr_key => $arr_value) { ?>
         <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
		<?php 
} ?>
   </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>

