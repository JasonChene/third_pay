<?php
include_once("../../../database/mysql.config.php");//原数据库的连接方式
include_once("../moneyfunc.php");
//$file = "log.txt";

//write_log("notify");
$input_data = file_get_contents("php://input");
//write_log($input_data);
if (!empty($input_data)) {
	$res = json_decode($input_data, 1);//json回传资料
	foreach ($res as $key => $value) {
		$data[$key] = $value;
		//write_log($key . "=" . $value);
	}
} else {
	foreach ($_REQUEST as $key => $value) {
		$data[$key] = $value;
		//write_log($key . "=" . $value);
	}
}

$MerNo = $data['MerNo'];// 商戶號
$Amount = $data['Amount'];// 訂單金額
$BillNo = $data['BillNo'];// 訂單號
$Succeed = $data['Succeed'];// 狀態碼

$MD5info = $data['MD5info'];// Return参数签名
$Result = $data['Result'];// 支付状态说明
$MerRemark = $data['MerRemark'];// 商户自定义备注信息

//file_put_contents($file,"\r\n==BillNo==".$BillNo,FILE_APPEND);
$params = array(':m_order' => $BillNo);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);//原数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);//原数据库的连接方式
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];

if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	//write_log("非法提交参数");
	exit;
}

$signtext = '';
$signtext .= "Amount=" . $Amount . "&";
$signtext .= "BillNo=" . $BillNo . "&";
$signtext .= "MerNo=" . $MerNo . "&";
$signtext .= "Succeed=" . $Succeed . "&";
$md5key = mb_strtoupper(md5($pay_mkey));
$signtext .= $md5key;
//write_log("signtext=" . $signtext);
$md5sign = mb_strtoupper(md5($signtext));
//file_put_contents($file,"\r\n==Succeed==".$Succeed,FILE_APPEND);
//if(notify回傳成功)
if ($Succeed == 88 || $Succeed == "88") {
	if ($MD5info == $md5sign) {
		$mymoney = number_format($Amount, 2, '.', '');
		$result_insert = update_online_money($BillNo, $mymoney);
		if ($result_insert == '-1') {
			echo ("会员信息不存在，无法入账");
			//write_log("会员信息不存在，无法入账");
		} else if ($result_insert == '0') {
			echo "SUCCESS";
			//write_log("SUCCESS at 0");
		} else if ($result_insert == '-2') {
			echo ("数据库操作失败");
			//write_log("数据库操作失败");
		} else if ($result_insert == '1') {
			echo "SUCCESS";
			//write_log("SUCCESS at 1");
		} else {
			echo ("支付失败");
			//write_log("支付失败");
		}
	} else {
		echo '签名不正确！';
		//write_log("签名不正确！");
		exit;
	}
}
?>