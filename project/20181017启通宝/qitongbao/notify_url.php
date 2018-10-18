<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.php");
// include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");

function rsa_decrypt($encrypted, $rsa_private_key)
{
	$crypto = '';
	$encrypted = base64_decode($encrypted);
	foreach (str_split($encrypted, 128) as $chunk) {
		openssl_private_decrypt($chunk, $decryptData, $rsa_private_key);
		$crypto .= $decryptData;
	}
	return $crypto;
}

#接收资料
$data = "";
$input_data = file_get_contents("php://input");
$notify_data = json_decode($input_data, 1);
//write_log($input_data);
$data = $notify_data['context'];//提取密文
//write_log($data);

//获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => "启通宝");
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
// $stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
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

#验签方式
$public_pem = chunk_split($pay_account, 64, "\r\n");//转换为pem格式的公钥
$public_pem = "-----BEGIN PUBLIC KEY-----\r\n" . $public_pem . "-----END PUBLIC KEY-----\r\n";
$private_pem = chunk_split($pay_mkey, 64, "\r\n");//转换为pem格式的私钥
$private_pem = "-----BEGIN RSA PRIVATE KEY-----\r\n" . $private_pem . "-----END RSA PRIVATE KEY-----\r\n";
#RSA-S验证
$privatekey = openssl_pkey_get_private($private_pem);
if ($privatekey == false) {
	echo "打开私钥出错";
	//write_log("打开私钥出错");
	exit();
}
$publickey = openssl_pkey_get_public($public_pem);
if ($publickey == false) {
	echo "打开公钥出错";
	//write_log("打开公钥出错");
	exit();
}
$data = rsa_decrypt($data, $privatekey);//执行解密流程
//write_log($data);
$context_arr = json_decode($data, true);

$businessContext = $context_arr['businessContext'];//取businessContext
//write_log('businessContext=' . $businessContext);
#设定固定参数
$order_no = $businessContext['memberOrderNumber']; //订单号
$mymoney = number_format($businessContext['tradeAmount'] / 100, 2, '.', ''); //订单金额
$success_msg = $notify_data['message']['code'];//成功讯息
$success_code = 200;//文档上的成功讯息
$echo_msg = "SUC";//回调讯息
$sign = $context_arr['businessHead']['sign'];//取SIGN
//write_log("sign=" . $sign);

ksort($businessContext);//按ASCII码从小到大排序
$json_businessContext = json_encode($businessContext, 320);
//write_log("json_businessContext=" . $json_businessContext);

$isVerify = (boolean)openssl_verify($json_businessContext, base64_decode($sign), $publickey, OPENSSL_ALGO_MD5);
//write_log("isVerify=" . $isVerify);
#到账判断
if ($success_msg == $success_code) {
	if ($isVerify == 1) {
		$result_insert = update_online_money($order_no, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			//write_log("会员信息不存在，无法入账");
			exit;
		} else if ($result_insert == 0) {
			echo ($echo_msg);
			//write_log($echo_msg . 'at 0');
			exit;
		} else if ($result_insert == -2) {
			echo ("数据库操作失败");
				// //write_log("数据库操作失败");
			exit;
		} else if ($result_insert == 1) {
			echo ($echo_msg);
			//write_log($echo_msg . 'at 1');
			exit;
		} else {
			echo ("支付失败");
			//write_log("支付失败");
			exit;
		}
	} else {
		echo ('签名不正确！');
		//write_log("签名不正确！");
		exit;
	}
} else {
	echo ("交易失败");
	//write_log("交易失败");
	exit;
}

?>
