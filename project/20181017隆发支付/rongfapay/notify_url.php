<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
// include_once("../../../database/mysql.config.php");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
// write_log("notify");

//解密
function decode($data,$private){   
	//读取秘钥
	$pr_key = openssl_pkey_get_private($private);
	if ($pr_key == false){
		echo "打开密钥出错";
		die;
	}
	$data = base64_decode($data);
	$crypto = '';
	//分段解密   
	foreach (str_split($data, 128) as $chunk) {
		openssl_private_decrypt($chunk, $decryptData, $pr_key);
		$crypto .= $decryptData;
	}
	// write_log('crypto=' . $crypto);
	return $crypto;
}

#接收资料
#post方法
$data = array();
foreach ($_POST as $key => $value) {
	$data[$key] = $value;
	// write_log($key."=".$value);
}

#设定固定参数
$order_no = $data['orderNo']; //订单号
$success_code = "00";//文档上的成功讯息
$echo_msg = "SUCCESS";//回调讯息

#根据订单号读取资料库
$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();

#获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];
$accountexp = explode('###',$pay_account);
$pay_md5key = $accountexp[0];
$pay_account = $accountexp[1];//商户公钥
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}
#私钥加头尾
$private_key = "-----BEGIN PRIVATE KEY-----\r\n";
foreach (str_split($pay_mkey,64) as $str){
	$private_key .= $str . "\r\n";
}
$private_key .="-----END PRIVATE KEY-----";
#RSA解密
$resultdata = $data['data'];
$resultJson=decode($resultdata,$private_key);
#验签方式
$resultarray = json_decode($resultJson,1);
$sign = $resultarray['sign'];//签名
$success_msg = $resultarray['payStateCode'];//成功讯息
// write_log("success_msg=".$success_msg);
$mymoney = number_format($resultarray['amount']/100, 2, '.', ''); //订单金额
// write_log("mymoney=".$mymoney);
ksort($resultarray);
$signtext = array();
foreach ($resultarray as $key => $value) {
	if($key !== 'sign'){
		$signtext[$key] = $value;
	}
}
$mysign = strtoupper(md5(json_encode($signtext,320) . $pay_md5key));//签名
// write_log("mysign=".$mysign);

#到账判断
if ($success_msg == $success_code) {
  if ( $mysign == $sign) {
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
