<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>充值接口-商户充值结果</title>
<?php
//include_once("../config.php");
include_once("../../../database/mysql.config.php");//原新数据库的连接方式
include_once("../moneyfunc.php");

$orderid = $_REQUEST['orderid'];

//$file = "log.txt";
//file_put_contents($file,"\r\n==orderid==".$orderid,FILE_APPEND);

$params = array(':m_order' => $orderid);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);//原新数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$payType = substr($row['operator'], 0, strripos($row['operator'], "_"));

$params = array(':pay_type' => $payType);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);//原新数据库的连接方式
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];

if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}
$parter = $pay_mid;
$check_key = $pay_mkey;
$opstate = $_REQUEST['opstate'];
$ovalue = $_REQUEST['ovalue'];
$systime = $_REQUEST['systime'];
$sysorderid = $_REQUEST['sysorderid'];
$p_sign = $_REQUEST['sign'];

$md5 = "orderid=" . $orderid . "&opstate=" . $opstate . "&ovalue=" . $ovalue . "&time=" . $systime . "&sysorderid=" . $sysorderid . $check_key;
$sign = md5($md5);
if ($sign == $p_sign) {
	//校验通过开始处理订单
	if ($opstate == "0") {
		$result_insert = update_online_money($orderid, $ovalue);
		if ($result_insert == -1) {
			echo ("opstate=0");
		} else if ($result_insert == 0) {
			echo ("opstate=0");
		} else if ($result_insert == -2) {
			echo ("opstate=0");
		} else if ($result_insert == 1) {
			echo ("opstate=0");
		} else {
			echo ("opstate=fail");
		}
	}
} else {
	echo ("opstate=Md5CheckFail"); //MD5校验失败，订单信息不显示
}
?>
</head>
<body>
</body>
</html>