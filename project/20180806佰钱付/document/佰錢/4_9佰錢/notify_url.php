<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

$MerNo = trim($_REQUEST['MerNo']);// 商戶號
$Amount = trim($_REQUEST['Amount']);// 訂單金額
$BillNo = trim($_REQUEST['BillNo']);// 訂單號
$Succeed = trim($_REQUEST['Succeed']);// 狀態碼

$MD5info = trim($_REQUEST['MD5info']);// Return参数签名
$Result = trim($_REQUEST['Result']);// 支付状态说明
$MerRemark = trim($_REQUEST['MerRemark']);// 商户自定义备注信息

$params = array(':m_order' => $BillNo);
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

$signtext = '';
$signtext .= "Amount=" . $Amount . "&";
$signtext .= "BillNo=" . $BillNo . "&";
$signtext .= "MerNo=" . $MerNo . "&";
$signtext .= "Succeed=" . $Succeed . "&";
$md5key = mb_strtoupper(md5($pay_mkey));
$signtext .= $md5key;
$md5sign = mb_strtoupper(md5($signtext));

//if(notify回傳成功)
if($Succeed == 88 || $Succeed == "88"){
	if ( $MD5info == $md5sign ) {
		$mymoney = number_format($Amount, 2, '.', '');
		$result_insert = update_online_money($BillNo, $mymoney);
		if ($result_insert == '-1') {
			echo ("会员信息不存在，无法入账");
		} else if ($result_insert == '0') {
			echo "SUCCESS";
		} else if ($result_insert == '-2') {
			echo ("数据库操作失败");
		} else if ($result_insert == '1') {
			echo "SUCCESS";
		} else {
			echo ("支付失败");
		}
	} else {
		echo '签名不正确！';
		exit;
	}
}
?>