<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
// include_once("../../../database/mysql.config.php");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

// write_log("notify");

#接收资料
$data = array();
foreach ($_POST as $key => $value) {
	$data[$key] = $value;
	// write_log($key."=".$value);
}

#设定固定参数
$order_no = $data['orderid']; //订单号
$mymoney = number_format($data['price'], 2, '.', ''); //订单金额
$sign = $data['key'];//签名
$echo_msg = "OK";//回调讯息

#根据订单号读取资料库
$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();

#获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
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
	// write_log("非法提交参数");
	exit;
}
$signtext = $data['orderid'].$data['orderuid'].$data['platform_trade_no'].$data['price'].$data['realprice'].$pay_mkey;
$mysign = md5($signtext);
// write_log("signtext=".$signtext);
// write_log("mysign=".$mysign);

#到账判断
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


?>
