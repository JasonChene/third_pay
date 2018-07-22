<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
#write_log("notify");

#接收资料
#request方法
$data = array();
foreach ($_REQUEST as $key => $value) {
	$data[$key] = $value;
	write_log($key . "=" . $value);
}

#设定固定参数
$order_no = $data['r6_Order']; //订单号
$mymoney = number_format($data['r3_Amt'], 2, '.', ''); //订单金额
$success_msg = $data['r1_Code'];//成功讯息
$success_code = "1";//文档上的成功讯息
$sign = $data['sign'];//签名
$echo_msg = "success";//回调讯息

#根据订单号读取资料库
$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();

#获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	write_log("非法提交参数");
	exit;
}

#验签方式
$signtext = $data['p1_MerId'] . $data['r0_Cmd'] . $data['r1_Code'] . $data['r2_TrxId'] . $data['r3_Amt'] . $data['r4_Cur'] . $data['r5_Pid'] . $data['r6_Order'] . $data['r7_Uid'] . $data['r8_MP'] . $data['r9_BType'];//验签字串
write_log("signtext=" . $signtext);
$mysign = md5($signtext);//签名
write_log("mysign=" . $mysign);
$platformPublicKey = openssl_get_publickey($pay_account);
if (!$platformPublicKey) {
	echo '打开公钥失败';
	exit;
}
$sign = str_replace("*", "+", $sign);
$sign = str_replace("-", "/", $sign);
write_log("sign=" . $sign);
$signsuccess = (boolean)openssl_verify($signtext, base64_decode($sign), $platformPublicKey);
openssl_free_key($platformPublicKey);
write_log("signsuccess=" . $signsuccess);

#到账判断
if ($success_msg == $success_code) {
	if ($signsuccess) {
		$result_insert = update_online_money($order_no, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			write_log("会员信息不存在，无法入账");
			exit;
		} else if ($result_insert == 0) {
			echo ($echo_msg);
			write_log($echo_msg . 'at 0');
			exit;
		} else if ($result_insert == -2) {
			echo ("数据库操作失败");
			write_log("数据库操作失败");
			exit;
		} else if ($result_insert == 1) {
			echo ($echo_msg);
			write_log($echo_msg . 'at 1');
			exit;
		} else {
			echo ("支付失败");
			write_log("支付失败");
			exit;
		}
	} else {
		echo ('签名不正确！');
		write_log("签名不正确！");
		exit;
	}
} else {
	echo ("交易失败");
	exit;
}

?>
