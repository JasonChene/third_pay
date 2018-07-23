<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");



$postdata = file_get_contents("php://input",'r');
//write_log($postdata);
$response = json_decode($postdata,1);
$orderAmt = trim($response['REP_BODY']['orderAmt']);
$orderId = trim($response['REP_BODY']['orderId']);
$orderState = trim($response['REP_BODY']['orderState']);
$payTime = trim($response['REP_BODY']['payTime']);
$sign = trim($response['REP_HEAD']['sign']);
$tranSeqId = trim($response['REP_BODY']['tranSeqId']);
//write_log($orderId);
//write_log($orderState);
#########$params = array(':m_order' => 訂單號);###########
$params = array(':m_order' => $orderId);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$idTemp = $payInfo['mer_account'];
$idArray = explode("###", $idTemp);
$md5key =$idArray[0];
$agtId =$idArray[1];//商户机构号
$public_key = $idArray[2];//商户公钥
if ($pay_mid == "" || $pay_mkey == "") {
echo ("非法提交参数");
exit;
}

ksort($response['REP_BODY']);
$noarr =array('sign');
foreach ($response['REP_BODY'] as $arr_key => $arr_val) {
	if ( !in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val ===0 || $arr_val ==='0') ) {
		$signText .= $arr_key.'='. $arr_val.'&' ;
	}
}


$signText = $signText.'key='.$md5key;
//write_log($signText);
$mysign = strtoupper(md5($signText));
//write_log($mysign);
$public_key = "-----BEGIN PUBLIC KEY-----\n" .wordwrap($public_key, 64, "\n", true) ."\n-----END PUBLIC KEY-----";
//write_log($public_key);
$pub_key_id = openssl_get_publickey($public_key);
$sign = base64_decode($sign);
$ok = openssl_verify($mysign,$sign,$pub_key_id, 'SHA256');
//write_log($ok);
$mymoney = number_format($orderAmt/100, 2, '.', '');
if ($orderState == "01") {
	if ($ok) {
		$result_insert = update_online_money($orderId,$mymoney);
			if ($result_insert === -1) {
				echo ("会员信息不存在，无法入账");
				//write_log('会员信息不存在，无法入账');
				exit;
			} else if ($result_insert === 0) {
				echo "SUCCESS";
				//write_log('SUCCESS at 0');
				exit;
			} else if ($result_insert === -2) {
				echo ("数据库操作失败");
				//write_log('数据库操作失败');
				exit;
			} else if ($result_insert === 1) {
				echo "SUCCESS";
				//write_log('SUCCESS at 1');
				exit;
			} else {
				echo ("支付失败");
				//write_log('支付失败');
				exit;
			}
	} else {
		echo '签名不正确！';
		//write_log('签名不正确！');
		exit;
	}} else {
	echo '交易失败！';
	//write_log('交易失败！');
	exit;
}
?>
