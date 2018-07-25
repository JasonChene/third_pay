<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
	date_default_timezone_set("Asia/Shanghai");
}
function curl_post($url, $data)
{ #POST访问
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
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
#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
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
$form_url = 'http://xinfugood.com:8081/trade/getBanks.do';
$data = array(
	"merchantId" => $pay_mid,
	"model" => 'online_bank'
);
$res = curl_post($form_url, http_build_query($data));
$tran = mb_convert_encoding($res, "UTF-8", "auto");
$row = json_decode($tran, 1);
echo '<pre>';
var_dump($row);
echo '</pre>';
?>

<!DOCTYPE html>
<html>
 	<head>
 		<meta charset="utf-8">
 		<title></title>
 	</head>
 	<body>
		<form action="<?php echo './post.php' ?>" method="get">
			<?php foreach ($_REQUEST as $arr_key => $arr_value) { ?>			
			<input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
			<?php 
	} ?>

		<div style="margin-left:2%;color:#f00">选择卡种</div><br/>
		<select name="card_type" style="width:96%;height:35px;margin-left:2%;">
		　<option value="01">储蓄卡</option>
		　<option value="02">信用卡</option>
		</select>
		<div style="margin-left:2%;color:#f00">选择银行</div><br/>
		<select name="bank_code" style="width:96%;height:35px;margin-left:2%;">
		　<option value="01">银行A</option>
		　<option value="02">银行B</option>
		</select>
		<div align="center">
			<input type="submit" value="送出" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" />
		</div>
		</form>
 	</body>
 </html>

