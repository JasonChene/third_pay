<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
// include_once("../../../database/mysql.config.php");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
// write_log("notify");

$data = array();

#接收资料
#post方法
// write_log('POST方法');
foreach ($_POST as $key => $value) {
	$data[$key] = $value;
	// write_log($key . "=" . $value);
}

#设定固定参数
$order_no = $data['orderNo']; //订单号


#根据订单号读取资料库
$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

#获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$idArray = explode("###", $pay_mkey);
$md5key = $idArray[0];//md5密钥
$private_key = $idArray[1];//RSA私钥：
$pay_account = $row['mer_account'];//RSA支付公钥
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	// write_log('非法提交参数');
	exit;
}
$public_pem = chunk_split($pay_account, 64, "\r\n");//转换为pem格式的公钥
$public_pem = "-----BEGIN PUBLIC KEY-----\r\n" . $public_pem . "-----END PUBLIC KEY-----\r\n";
$private_pem = chunk_split($private_key, 64, "\r\n");//转换为pem格式的私钥
$private_pem = "-----BEGIN RSA PRIVATE KEY-----\r\n" . $private_pem . "-----END RSA PRIVATE KEY-----\r\n";

$data = $_POST['data'];
$pr_key = openssl_get_privatekey($private_pem);
if ($pr_key == false){
	echo "打开密钥出错";
	die;
}
$data = base64_decode($data);
$crypto = '';
foreach (str_split($data, 128) as $chunk) {
	openssl_private_decrypt($chunk, $decryptData, $pr_key);
	$crypto .= $decryptData;
}
$array = array();
$array = json_decode($crypto,1);

$mymoney = number_format($array['amount']/100, 2, '.', ''); //订单金额
$success_msg = $array['payStateCode'];//成功讯息
$success_code = "00";//文档上的成功讯息
$sign = $array['sign'];;//签名
$echo_msg = "SUCCESS";//回调讯息

ksort($array);
$sign_array = array();
foreach ($array as $k => $v) {
	if ($k !== 'sign'){
		$sign_array[$k] = $v;
		// write_log($k . "=" . $v);
	}
}
$signtext = json_encode($sign_array,320) . $md5key;
$mysign =  strtoupper(md5($signtext));

// write_log("signtext=".$signtext);
// write_log("mysign=".$mysign);


#到账判断
if ($success_msg == $success_code) {
	if ($mysign == $sign) {
		$result_insert = update_online_money($order_no, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			// write_log("会员信息不存在，无法入账");
			exit;
		} else if ($result_insert == 0) {
			echo ($echo_msg);
			// write_log($echo_msg . 'at 0');
			exit;
		} else if ($result_insert == -2) {
			echo ("数据库操作失败");
			// write_log("数据库操作失败");
			exit;
		} else if ($result_insert == 1) {
			echo ($echo_msg);
			// write_log($echo_msg . 'at 1');
			exit;
		} else {
			echo ("支付失败");
			// write_log("支付失败");
			exit;
		}
	} else {
		echo ('签名不正确！');
		// write_log("签名不正确！");
		exit;
	}
} else {
	echo ("交易失败");
	// write_log("交易失败");
	exit;
}

?>
