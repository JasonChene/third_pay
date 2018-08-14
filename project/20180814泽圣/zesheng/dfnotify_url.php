<? header("content-Type: text/html; charset=UTF-8");?>
<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");


//$file = "log.txt";

$orderno=$_POST["outOrderId"];//商户订单号
//file_put_contents($file,"\r\n==orderno==".$orderno,FILE_APPEND);
$params = array(':m_order'=>$orderno);
$sql = "select m_id,status,about from k_money where m_order=:m_order and type='2'";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$m_id = $row['m_id'];
$status = $row['status'];
$about = $row['about']."泽圣代付_df";

$params = array(':pay_name'=>'泽圣');
$sql = "select * from pay_set where pay_name=:pay_name and is_df='1'";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];

if($m_id == "" || $pay_mkey == ""|| $pay_mid == "")
{
	echo "非法提交参数";
	exit;
}
if($status == "1")
{
	echo "订单已支付成功";
	exit;
}
$data					= array();
$data['merchantCode']	=$pay_mid;
$data['outOrderId']		=$_POST["outOrderId"];//商户订单号
$data['orderId']		=$_POST["orderId"];
$data['state']			=$_POST["state"];//00-付款成功02-付款失败
$data['transTime']		=$_POST["transTime"];
$data['totalAmount']	=$_POST["totalAmount"];//充值金额（分）
$data['fee']			=$_POST["fee"];//手续费
$errorMsg				=$_POST["errorMsg"];//付款失败时有值
$sign					=$_POST["sign"];//md5签名

ksort($data);
$sign_src = "";
foreach ($data as $key=>$value) {
    $sign_src .= $key . "=" . $value . "&";
}
$sign_src .= "KEY=" . $pay_mkey;

$signture =strtoupper(MD5($sign_src));

//file_put_contents($file,"\r\n==outOrderId==".$_POST["outOrderId"],FILE_APPEND);
//file_put_contents($file,"\r\n==orderId==".$_POST["orderId"],FILE_APPEND);
//file_put_contents($file,"\r\n==state==".$_POST["state"],FILE_APPEND);
//file_put_contents($file,"\r\n==transTime==".$_POST["transTime"],FILE_APPEND);
//file_put_contents($file,"\r\n==totalAmount==".$_POST["totalAmount"],FILE_APPEND);
//file_put_contents($file,"\r\n==fee==".$_POST["fee"],FILE_APPEND);
//file_put_contents($file,"\r\n==errorMsg==".$_POST["errorMsg"],FILE_APPEND);

//file_put_contents($file,"\r\n==signture==".$signture,FILE_APPEND);
//file_put_contents($file,"\r\n==sign==".$sign,FILE_APPEND);
if($sign == $signture){
	if($_POST["state"]=="00"){
		$result_insert = update_orderstatus($orderno,$about,$data['fee']/100);
//file_put_contents($file,"\r\n==result_insert==".$result_insert,FILE_APPEND);
    	if ($result_insert==-1) {
			echo("fail error 1");
		} else if ($result_insert==0) {
			echo("success");
		} else if ($result_insert==-2) {
			echo("fail error 2");
		} else if ($result_insert==1) {
			echo("success}");
		} else {
			echo("fail error 3");
		}
	}
}	
else{
	echo "fail error 5";
}


?>
