<? header("content-Type: text/html; charset=utf-8"); ?>
<?php
// include_once("../../../database/mysql.config.php");
include_once("../../../database/mysql.php");
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
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
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
		$result_insert = update_online_money($ordernumber, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			//write_log("会员信息不存在，无法入账");
			exit;
		}else if($result_insert == 0){
			echo ($echo_msg);
			//write_log($echo_msg.'at 0');
			exit;
		}else if($result_insert == -2){
			echo ("数据库操作失败");
			//write_log("数据库操作失败");
			exit;
		}else if($result_insert == 1){
			echo ($echo_msg);
			//write_log($echo_msg.'at 1');
			exit;
		} else {
			echo ("支付失败");
			//write_log("支付失败");
			exit;
		}
}else{
	echo ("交易失败");
	//write_log("交易失败");
	exit;
}

?>
