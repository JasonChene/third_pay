<? header("content-Type: text/html; charset=UTF-8");?>
<?php

//include_once("../config.php");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

$orderno=$_POST["outOrderId"];//商户订单号

$params = array(':m_order'=>$orderno);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$payType = substr($row['operator'] , 0 , strripos($row['operator'],"_"));

$params = array(':pay_type'=>$payType);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
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

$merchantCode=$_POST["merchantCode"];
$instructCode=$_POST["instructCode"];
$transType=$_POST["transType"];
$outOrderId=$_POST["outOrderId"];//商户订单号
$transTime=$_POST["transTime"];
$totalAmount=$_POST["totalAmount"];//充值金额（分）
$ext =$_POST["ext"];
$sign=$_POST["sign"];//md5签名

$temp = "";
$temp = $temp."instructCode=".$instructCode."&";
$temp = $temp."merchantCode=".$merchantCode."&";
$temp = $temp."outOrderId=".$outOrderId."&";
$temp = $temp."totalAmount=".$totalAmount."&";
$temp = $temp."transTime=".$transTime."&";
$temp = $temp."transType=".$transType."&";
$temp = $temp."KEY=".$pay_mkey;

$signture =strtoupper(MD5($temp));


if($sign == $signture){
		$result_insert = update_online_money($outOrderId,($totalAmount/100));
    	if ($result_insert==-1) {
			echo("fail error 1");
		} else if ($result_insert==0) {
			echo("{'code':'00'}");
		} else if ($result_insert==-2) {
			echo("fail error 2");
		} else if ($result_insert==1) {
			echo("{'code':'00'}");
		} else {
			echo("fail error 3");
		}
}	
else{
	echo "fail error 5";
}

?>