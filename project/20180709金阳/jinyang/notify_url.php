<?php session_start(); ?>
<? header("content-Type: text/html; charset=UTF-8");?>
<?php
//include_once("../config.php");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

$orderid    = $_REQUEST['ordernumber'];

//$file = "log.txt";
//file_put_contents($file,"\r\n==orderid==".$orderid,FILE_APPEND);

$params = array(':m_order'=>$orderid);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$payType = substr($row['operator'] , 0 , strripos($row['operator'],"_"));

$params = array(':pay_type'=>$payType);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
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

$partner		= $pay_mid;
$ordernumber    = $_REQUEST['ordernumber'];
$orderstatus    = $_REQUEST['orderstatus'];
$paymoney		= $_REQUEST['paymoney'];
$sysnumber		= $_REQUEST['sysnumber'];
$attach			= $_REQUEST['attach'];
$sign			= $_REQUEST['sign'];

$md5 = "partner=".$partner."&ordernumber=".$ordernumber."&orderstatus=".$orderstatus."&paymoney=".$paymoney.$pay_mkey;
$signtrue  = md5($md5);

//file_put_contents($file,"\r\n==sign==".$sign,FILE_APPEND);
//file_put_contents($file,"\r\n==signtrue==".$signtrue,FILE_APPEND);
if($sign == $signtrue){
	//校验通过开始处理订单
//file_put_contents($file,"\r\n==orderstatus==".$orderstatus,FILE_APPEND);
	if ($orderstatus == "1") {
		$result_insert = update_online_money($ordernumber,$paymoney);
//file_put_contents($file,"\r\n==result_insert==".$result_insert,FILE_APPEND);
		if ($result_insert==-1) {
			echo("fail");
		} else if ($result_insert==0) {
			echo("ok");
		} else if ($result_insert==-2) {
			echo("fail");
		} else if ($result_insert==1) {
			echo("ok");
		} else {
			echo("fail");
		}
	}
}else{
	echo("opstate=Md5CheckFail"); //MD5校验失败，订单信息不显示
}
?>
</head>
<body>
</body>
</html>