<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>充值接口-商户充值结果</title>
<?php
include_once("../config.php");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

$ordernumber = $_POST["orderid"];

$params = array(':m_order'=>$ordernumber);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$payType = substr($row['operator'] , 0 , strripos($row['operator'],"_"));

$params = array(':pay_type'=>$payType);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];

if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}
$ReturnArray = array( // 返回字段
	"memberid" => $_POST["memberid"], // 商户ID
	"orderid" =>  $_POST["orderid"], // 订单号
	"amount" =>  $_POST["amount"], // 交易金额
	"datetime" =>  $_POST["datetime"], // 交易时间
	"returncode" => $_POST["returncode"]
);
$Md5key = $pay_mkey;
   
ksort($ReturnArray);
reset($ReturnArray);
$md5str = "";
foreach ($ReturnArray as $key => $val) {
	$md5str = $md5str . $key . "=" . $val . "&";
}
$sign = strtoupper(md5($md5str . "key=" . $Md5key)); 

if ($sign == $_POST["sign"]) {
	//校验通过开始处理订单
	 if ($_POST["returncode"] == "00000") {
		$result_insert = update_online_money($ordernumber,$_POST["amount"]);
		if ($result_insert==-1) {
			echo("success");
		} else if ($result_insert==0) {
			echo("success");
		} else if ($result_insert==-2) {
			echo("success");
		} else if ($result_insert==1) {
			echo("success");
		} else {
			echo("fail");
		}
	}
}else{
	echo("Md5CheckFail"); //MD5校验失败，订单信息不显示
}
?>
</head>
<body>
</body>
</html>