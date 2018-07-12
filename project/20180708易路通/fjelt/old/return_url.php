<? header("content-Type: text/html; charset=utf-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
//include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

//write_log("return");

#post方法
//write_log('post方法');
foreach ($_POST as $key => $value) {
	$data[$key] = $value;
	//write_log($key."=".$value);
}


$params = array(':pay_name' => '易路通');
$sql = "select * from pay_set where pay_name=:pay_name";
$stmt = $mydata1_db->prepare($sql);
//$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}

#解析密文
$method = $_POST['Method'];
$data   = $_POST['Data'];
$sign   = $_POST['Sign'];
$appid  = $_POST['Appid'];
$mySign = strtolower(md5($data . $pay_mkey));
//write_log("mySign=".$mySign);
if ($mySign != $sign) {    
	exit(json_encode(['message' => '验证签名失败', 'response' => '01']));
}
$aes_data =base64_decode( str_replace('-','+',str_replace('_', '/',  $data)));
//write_log("aes_data=".$aes_data);
$input = openssl_decrypt($aes_data,'AES-128-CBC',$pay_mkey,OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $pay_mkey);
//write_log("input=".$input);
$result   = json_decode(rtrim($input, "\0"),TRUE);
//write_log("result=".$result);
foreach ($result as $key => $value) {
	//write_log($key."=".$value);
}
if ($method == 'paymentreport') {    
	$ordernumber  = $result['ordernumber']; //商户订单号    
	$amount       = $result['amount']; //交易金额    
	$payorderid   = $result['payorderid']; //交易流水号    
	$busin= $result['businesstime']; //交易时间yyyy-MM-dd hh:mm:ss    
	$respcode     = $result['respcode']; //交易状态 1-待支付 2-支付完成 3-已关闭 4-交易撤销    
	$extraparams  = $result['extraparams']; //扩展内容 原样返回    
	$respmsg      = $result['respmsg']; //状态说明    
	//这边写你支付完成的业务逻辑    
	//处理成功返回    
	$mymoney = number_format($amount/100, 2, '.', ''); //订单金额
	$echo_msg = json_encode(['message' => 'success', 'response' => '00']);
}else {
	$echo_msg = json_encode(['message' => '未识别的Method', 'response' => '01']);
} 

#到账判断
if ($respcode == "2") {
		$result_insert = update_online_money($order_no, $mymoney);
		if ($result_insert == -1) {
			$message = ("会员信息不存在，无法入账");
		}else if($result_insert == 0){
			$message = ("支付成功");
		}else if($result_insert == -2){
			$message = ("数据库操作失败");
		}else if($result_insert == 1){
			$message = ("支付成功");
		} else {
			$message = ("支付失败");
		}
}else{
	$message = ("交易失败");
}
?>

<!-- Html顯示充值資訊 須改變訂單echo變數名稱-->
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
				<label id="lborderno"><?php echo $ordernumber; ?></label>
			</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">充值金额：</td>
			<td style="padding-left: 10px;">
				<label id="lbpayamount"><?php echo $amount; ?></label>
			</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">处理结果：</td>
			<td style="padding-left: 10px;">
				<label id="lbmessage"><?php echo $message; ?></label>
			</td>
		</tr>
		
	</table>
</body>
</html>
