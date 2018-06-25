<? header("content-Type: text/html; charset=UTF-8");?>
<?php
//include_once("../config.php");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

//$file = "log.txt";

$ip = getClientIp();

//file_put_contents($file,"\r\n==ip==".$ip,FILE_APPEND);

if($ip !="183.14.132.98" && $ip !="103.70.76.227" &&$ip !="103.70.76.226"&&$ip !="111.68.14.74"){
	echo "回调非法";
	exit;
}


$orderno = $_GET["ordernumber"]; //网站支付的订单号

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
$pay_account = $payInfo['mer_account'];

if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}

$partner = $pay_mid;//商户ID
$Key = $pay_mkey;//商户KEY
$orderstatus = $_GET["orderstatus"];
$paymoney = $_GET["paymoney"];
$sign = $_GET["sign"];
$attach = $_GET["attach"]; 

$signSource = sprintf("partner=%s&ordernumber=%s&orderstatus=%s&paymoney=%s%s", $partner, $orderno, $orderstatus, $paymoney, $Key); 
$md5str =  md5($signSource);
	
	if ($sign == $md5str){
		if($_GET["orderstatus"] == "1"){
			$result_insert = update_online_money($orderno,$paymoney);
			if ($result_insert==-1) {
				echo "ok";
				exit;
			} else if ($result_insert==0) {
				echo "ok";
				exit;
			} else if ($result_insert==-2) {
				echo "ok";
				exit;
			} else if ($result_insert==1) {
				echo "ok";
				exit;
			} else {
				echo("支付失败");
			}
		}
		else 
		{
			echo("支付失败");
		}	
	}else{
		$result="Signature Error";
	}

?>