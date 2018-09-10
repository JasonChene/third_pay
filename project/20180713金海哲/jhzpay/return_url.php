<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
// include_once("../../../database/mysql.config.php");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

// write_log('return:');
$ret = $_REQUEST['ret'];
$msg = $_REQUEST['msg'];
$arr_ret = json_decode($ret, 1);
$arr_msg = json_decode($msg, 1);
$result = $arr_ret["code"];//支付结果
$pay_message = $arr_ret["msg"]; //支付结果消息，支付成功为空
$orderno = $arr_msg['no']; //网站支付的订单号
$amount = $arr_msg['money']; //支付总金额

// write_log("arr_ret:");
// foreach ($arr_ret as $key => $value) {
//     write_log($key . "=" . $value);
// }

// write_log("arr_msg:");
// foreach ($arr_msg as $key => $value) {
//     write_log( $key . "=" . $value);
// }



$params = array(':m_order'=>$orderno);
$sql = "select operator from k_money where m_order=:m_order";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$payType = substr($row['operator'] , 0 , strripos($row['operator'],"_"));

$params = array(':pay_type'=>$payType);
$sql = "select * from pay_set where pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
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
//////////////////
$public_key = $pay_account;
$pu_key = '';
if(openssl_pkey_get_public($public_key)){
    $pu_key = openssl_pkey_get_public($public_key);
	$datas = stripslashes($_REQUEST['ret'].'|'.$_REQUEST['msg']);
	//验签
	$txt = openssl_verify($datas,base64_decode($_REQUEST['sign']),$pu_key);
	openssl_free_key($pu_key);
	if(1==$txt){
		$message = '支付成功，详细交易结果以交易记录为准。';
	}else{
		$message = '支付失敗';
		exit;
	}
}else{
	$message = '支付失敗';
	exit;
}

// write_log('message='.$message);
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>支付同步结果展示</title>
    <style type="text/css">
        *,html,body{ background: #fff;font-size: 14px;font-family: "Microsoft Yahei", "微软雅黑"}
        html,body{ width: 100%;margin: 0;padding: 0;}
        table .tips{ background: #F0F0FF;height: 35px;line-height: 35px;padding-left: 5px;font-weight: 600;}
    </style>
</head>
<body>
	<table width="98%" border="1" cellspacing="0" cellpadding="3" bordercolordark="#fff" bordercolorlight="#d3d3d3" style="margin: 10px auto;">
    <tr>
			<td colspan="2" class="tips">处理结果</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">订单号：</td>
			<td style="padding-left: 10px;">
				<label id="lborderno"><?php echo $orderno; ?></label>
			</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">充值金额：</td>
			<td style="padding-left: 10px;">
				<label id="lbpayamount"><?php echo number_format($amount/100, 2, '.', ''); ?></label>
			</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">处理结果：</td>
			<td style="padding-left: 10px;">
				<label id="lbmessage"><?php echo $message; ?></label>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="button" value="关闭"/>
			</td>
		</tr>
	</table>
</body>
</html>