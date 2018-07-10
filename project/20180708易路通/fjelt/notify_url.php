<? header("content-Type: text/html; charset=utf-8"); ?>
<?php
// include_once("../../../database/mysql.config.php");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

write_log("notify");

#############################################
#request方法
write_log('request方法');
foreach ($_REQUEST as $key => $value) {
	$data[$key] = $value;
	write_log($key."=".$value);
}
#post方法
write_log('post方法');
foreach ($_POST as $key => $value) {
	$data[$key] = $value;
	write_log($key."=".$value);
}
#input方法
write_log('input方法');
$input_data=file_get_contents("php://input");

$res=json_decode($input_data,1);//json回传资料

// $xml=(array)simplexml_load_string($input_data) or die("Error: Cannot create object");
// $res=json_decode(json_encode($xml),1);//XML回传资料

// $xml=(array)simplexml_load_string($input_data,'SimpleXMLElement',LIBXML_NOCDATA) or die("Error: Cannot create object");
// $res=json_decode(json_encode($xml),1);//XMLCDATA回传资料

foreach ($res as $key => $value) {
	$data[$key] = $value;
	write_log($key."=".$value);
}
###########################################
// $data = array();
#接收资料
#post方法
write_log('post方法');
foreach ($_POST as $key => $value) {
	// $data[$key] = $value;
	write_log($key."=".$value);
}

#设定固定参数
// $order_no = $data['BusinessOrders']; //订单号
// $mymoney = number_format($data['Amount']/100, 2, '.', ''); //订单金额
// $success_msg = $data['OrderStatus'];//成功讯息
// $success_code = "SUCCESS";//文档上的成功讯息(根本沒有)
// $sign = base64_decode($data['Sign']);//签名
// $echo_msg = "SUCCESS";//回调讯息

#根据订单号读取资料库
// $params = array(':m_order' => $order_no);
// $sql = "select operator from k_money where m_order=:m_order";
// // $stmt = $mydata1_db->prepare($sql);
// $stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
// $stmt->execute($params);
// $row = $stmt->fetch();

#获取该订单的支付名称
// $pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
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
if ($mySign != $sign) {    exit(json_encode(['message' => '验证签名失败', 'response' => '01']));}
$aes_data =base64_decode( str_replace('-','+',str_replace('_', '/',  $data)));
$input = openssl_decrypt($aes_data,'AES-128-CBC',$pay_mkey,OPENSSL_RAW_DATA, $pay_mkey);
$result   = json_decode(rtrim($input, "\0"),TRUE);

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
if ($success_msg == $success_code) {
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
