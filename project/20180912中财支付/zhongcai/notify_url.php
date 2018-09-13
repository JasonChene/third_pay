<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
// include_once("../../../database/mysql.config.php");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");

// write_log("notify");
function pub_decrypt_rsa($data, $pi_key){
	$split = str_split($data, 172);// 1024bit  固定172
	$decode_data = '';
	foreach ($split as $part) {
		$isOkay = openssl_public_decrypt(base64_decode($part), $de_data, $pi_key);// base64在这里使用，因为172字节是一组，是encode来的
		if(!$isOkay){
			return false;
		}
	$decode_data .= $de_data;
	}
	return $decode_data;
}

#post方法
$res = array();
// write_log('post方法');
foreach ($_POST as $key => $value) {
	$res[$key] = $value;
	// write_log($key."=".$value);
}

// #根据订单号读取资料库
// $params = array(':m_order' => $order_no);
// $sql = "select operator from k_money where m_order=:m_order";
// // $stmt = $mydata1_db->prepare($sql);
// $stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
// $stmt->execute($params);
// $row = $stmt->fetch();

// #获取该订单的支付名称
// $pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => '中财支付');
$sql = "select * from pay_set where pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	// write_log('非法提交参数');
	exit;
}

#验签方式
$data2 = array();
$pu_key = openssl_pkey_get_public($pay_mkey);
$data = pub_decrypt_rsa($res['info'],$pu_key);
$data = json_decode($data,true);
foreach ($data as $key => $value) {
	$data2[$key] = $value;
	// write_log($key."=".$value);
}

#设定固定参数
$order_no = $data2['order_id']; //订单号
$mymoney = number_format($data2['money'], 2, '.', ''); //订单金额
$success_msg = $data2['status'];//成功讯息
$success_code = 200;//文档上的成功讯息
$sign = $data2['key'];//签名
$echo_msg = '{"status":200}';//回调讯息

$signtext = $data2['rank'].$pay_account;
$mysign = md5($signtext);

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
