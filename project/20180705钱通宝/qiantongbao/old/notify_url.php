<? header("content-Type: text/html; charset=gb2312"); ?>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

$orderid = mb_convert_encoding(trim($_REQUEST['orderid']), "UTF-8","GB2312");//交易订单号
$opstate = mb_convert_encoding(trim($_REQUEST['opstate']), "UTF-8","GB2312");//交易结果
$ovalue = mb_convert_encoding(trim($_REQUEST['ovalue']), "UTF-8","GB2312");//交易金额
$sign = mb_convert_encoding(trim($_REQUEST['sign']), "UTF-8","GB2312");//签名


$params = array(':m_order' => $orderid);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));

$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];

if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}
$parms=array(
	"orderid" => $orderid,//交易订单号
	"opstate" => $opstate,//交易结果
	"ovalue" => $ovalue,//交易金额
);
$signtext='';
foreach ($parms as $key => $arr_value) {
	$signtext .= $key . '=' . $arr_value . '&';
}
$signtext=substr($signtext,0,-1).$pay_mkey;
$mysign=mb_strtolower(md5($signtext));
$success_echo = "opstate=0";
$success_echo2=mb_convert_encoding($success_echo, "GB2312","UTF-8");//UTF-8转码GB2312

if ($sign == $mysign) {
	$result_insert = update_online_money($orderid, $ovalue);
	if ($result_insert == -1) {
		echo ("会员信息不存在，无法入账");	
	}else if($result_insert == 0){
		echo ($success_echo2);
	}else if($result_insert == -2){
		echo ("数据库操作失败");
	}else if($result_insert == 1){
		echo ($success_echo2);
	} else {
		echo ("支付失败");
	}
}else{
	echo '签名不正确！';
	exit;
}

?>
