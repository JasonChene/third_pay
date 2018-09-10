<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");
function verify($plainText, $sign, $cert){
	$resource = openssl_pkey_get_public($cert);
	$result = openssl_verify($plainText, base64_decode($sign), $resource);
    openssl_free_key($resource);
    return $result;
}

// write_log("notify--------------------------");



#接收资料
#post方法
// write_log("POST方法--------");
$data = array();
foreach ($_POST as $key => $value) {
	$data[$key] = $value;
	// write_log($key."=".$value);
}
$notify_data = stripslashes($_POST['data']); //支付数据
$notify_data_arr = json_decode($notify_data,1);

#设定固定参数
$order_no = $notify_data_arr['body']['orderCode']; //订单号
$mymoney = number_format($notify_data_arr['body']['totalAmount']/100, 2, '.', ''); //订单金额
$success_msg = $notify_data_arr['body']['orderStatus'];//成功讯息
$success_code = "1";//文档上的成功讯息
$sign = $data['sign'];//签名
$echo_msg = "respCode=000000";//回调讯息

#根据订单号读取资料库
$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

#获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	// write_log("非法提交参数");
	exit;
}

#验签方式
// write_log(verify($notify_data, $sign, $pay_account));
#到账判断
if ($success_msg == $success_code) {
  if (verify($notify_data, $sign, $pay_account)) {
		$result_insert = update_online_money($order_no, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			// write_log("会员信息不存在，无法入账");
			exit;
		}else if($result_insert == 0){
			echo ($echo_msg);
			// write_log($echo_msg.'at 0');
			exit;
		}else if($result_insert == -2){
			echo ("数据库操作失败");
			// write_log("数据库操作失败");
			exit;
		}else if($result_insert == 1){
			echo ($echo_msg);
			// write_log($echo_msg.'at 1');
			exit;
		} else {
			echo ("支付失败");
			// write_log("支付失败");
			exit;
		}
	}else{
		echo ('签名不正确！');
		// write_log("签名不正确！");
		exit;
	}
}else{
	echo ("交易失败");
	// write_log("交易失败");
	exit;
}

?>
