<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
// include_once("../../../database/mysql.config.php");//原新数据库的连接方式
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
#write_log("notify");


#############################################
#request方法
write_log('request方法');
foreach ($_REQUEST as $key => $value) {
	write_log($key . "=" . $value);
}
#post方法
write_log('post方法');
foreach ($_POST as $key => $value) {
	write_log($key . "=" . $value);
}
#input方法
write_log('input方法');
$input_data = file_get_contents("php://input");
write_log($input_data);
// $res=json_decode($input_data,1);//json回传资料

// $xml=(array)simplexml_load_string($input_data) or die("Error: Cannot create object");
// $res=json_decode(json_encode($xml),1);//XML回传资料

// $xml=(array)simplexml_load_string($input_data,'SimpleXMLElement',LIBXML_NOCDATA) or die("Error: Cannot create object");
// $res=json_decode(json_encode($xml),1);//XMLCDATA回传资料

// foreach ($res as $key => $value) {
// 	$data[$key] = $value;
// 	write_log($key."=".$value);
// }
###########################################


#接收资料
#request方法
$data = array();
foreach ($_REQUEST as $key => $value) {
	$data[$key] = $value;
	write_log($key . "=" . $value);
}

#设定固定参数
$order_no = $data['order_no']; //订单号
$mymoney = number_format($data['pay_amoumt'], 2, '.', ''); //订单金额
$success_msg = $data['is_success'];//成功讯息
$success_code = "1";//文档上的成功讯息
$sign = $data['sign'];//签名
$echo_msg = "";//回调讯息

#根据订单号读取资料库
$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
// $stmt = $mydata1_db->prepare($sql);//原新数据库的连接方式
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();

#获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);//原新数据库的连接方式
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
ksort($data);
$noarr = array('sign');//不加入签名的array key值
$signtext = "";
foreach ($data as $arr_key => $arr_val) {
	if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
		$signtext .= $arr_key . '=' . $arr_val . '&';
	}
}
$signtext = substr($signtext, 0, -1);//验签字串
write_log("signtext=" . $signtext);
$mysign = md5($signtext);//签名
write_log("mysign=" . $mysign);

#到账判断
if ($success_msg == $success_code) {
	if ($mysign == $sign) {
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
