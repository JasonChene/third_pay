<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");


$merchant_code	= $_POST["merchant_code"];

$interface_version = $_POST["interface_version"];

$sign_type = $_POST["sign_type"];

$dinpaySign = base64_decode($_POST["sign"]);

$notify_type = $_POST["notify_type"];

$notify_id = $_POST["notify_id"];

$order_no = $_POST["order_no"];

$order_time = $_POST["order_time"];

$order_amount = $_POST["order_amount"];

$trade_status = $_POST["trade_status"];

$trade_time = $_POST["trade_time"];

$trade_no = $_POST["trade_no"];

$bank_seq_no = $_POST["bank_seq_no"];

$extra_return_param = $_POST["extra_return_param"];

$params = array(':m_order'=>$order_no);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$pay_type = substr($row['operator'] , 0 , strripos($row['operator'],"_"));

$params = array(':pay_type'=>$pay_type);
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

$debaozhifu_public_key = $pay_account;

/////////////////////////////   参数组装  /////////////////////////////////
/**
除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
*/


	$signStr = "";

	if($bank_seq_no != ""){
		$signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
	}

	if($extra_return_param != ""){
		$signStr = $signStr."extra_return_param=".$extra_return_param."&";
	}

	$signStr = $signStr."interface_version=".$interface_version."&";

	$signStr = $signStr."merchant_code=".$merchant_code."&";

	$signStr = $signStr."notify_id=".$notify_id."&";

	$signStr = $signStr."notify_type=".$notify_type."&";

  $signStr = $signStr."order_amount=".$order_amount."&";

  $signStr = $signStr."order_no=".$order_no."&";

  $signStr = $signStr."order_time=".$order_time."&";

  $signStr = $signStr."trade_no=".$trade_no."&";

  $signStr = $signStr."trade_status=".$trade_status."&";

	$signStr = $signStr."trade_time=".$trade_time;



/////////////////////////////   RSA-S验证  /////////////////////////////////

	$dinpay_public_key = openssl_get_publickey($debaozhifu_public_key);

	$flag = openssl_verify($signStr,$dinpaySign,$debaozhifu_public_key,OPENSSL_ALGO_MD5);

	if ($flag){
		if($trade_status == "SUCCESS"){
			$result_insert = update_online_money($order_no,$order_amount);
			if ($result_insert==-1) {
				echo ("会员信息不存在，无法入账");
				exit;
			} else if ($result_insert==0) {
				echo "SUCCESS";
				exit;
			} else if ($result_insert==-2) {
				echo ("数据库操作失败");
				exit;
			} else if ($result_insert==1) {
				echo "SUCCESS";
				exit;
			} else {
				echo("交易失败！");
			}
		}
		else
		{
			echo("支付失败");
		}
	}else{
		echo "签名不正确！";
	}

?>
