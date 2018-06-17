<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>充值接口-服务器返回结果</title>
<?php
include_once("../config.php");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

$result = $_REQUEST['result'];//支付结果
$pay_message = $_REQUEST['pay_message']; //支付结果消息，支付成功为空
$usercode = $_REQUEST['usercode']; //商户号
$plat_billid = $_REQUEST['plat_billid']; // 丰达平台订单号
$orderno = $_REQUEST['orderno']; //网站支付的订单号
$paytype = $_REQUEST['paytype']; //支付类型
$value = $_REQUEST['value']; //支付总金额
$remark = $_REQUEST['remark']; //商户自定义，原样返回
$sign = $_REQUEST['sign'];

$params = array(':m_order'=>$orderno);
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
$pay_account = $row['mer_account'];
if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}

$curdata[0] = substr($remark , 0 , 4);
$curdata[1] = substr($remark , 4);

if(md5($usercode.$curdata[0]) != $curdata[1])
{
	echo '签名不正确！';
	exit;	
}


$Md5key = $pay_mkey; ///////////md5密钥（KEY）

//MD5签名格式
$WaitSign=md5($result.$usercode.$plat_billid.$orderno.$paytype.$value.$Md5key);

if($sign == $WaitSign){
	//校验通过开始处理订单

	if ($result == 1) {
		$result_insert = update_online_money($orderno,$value);
		if ($result_insert==-1) {
			echo("会员信息不存在，无法入账");
		} else if ($result_insert==0) {
			echo("会员已经入账，无需重复入账");
		} else if ($result_insert==-2) {
			echo("数据库操作失败");
		} else if ($result_insert==1) {
			echo("ok");//全部正确了输出OK
			echo("<br />支付成功");
			echo("<br/>交易金额：".$m_oamount);
		} else {
			echo("支付失败");
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