<? header("content-Type: text/html; charset=utf-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
//include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

$data = array();
#接收资料
$input_data=file_get_contents("php://input");
$res=json_decode($input_data,1);//json回传资料
foreach ($res as $key => $value) {
	$data[$key] = $value;
	//write_log($key."=".$value);
}

#设定固定参数
$order_no = $data['BusinessOrders']; //订单号
$mymoney = number_format($data['Amount']/100, 2, '.', ''); //订单金额
$success_msg = $data['OrderStatus'];//成功讯息
$success_code = "SUCCESS";//文档上的成功讯息(根本沒有)
$sign = base64_decode($data['Sign']);//签名
$echo_msg = "SUCCESS";//回调讯息

#根据订单号读取资料库
$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);
//$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

#获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
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

#验签方式
$public_pem = chunk_split($pay_account,64,"\r\n");//转换为pem格式的公钥
$public_pem = "-----BEGIN PUBLIC KEY-----\r\n".$public_pem."-----END PUBLIC KEY-----\r\n";
$private_pem = chunk_split($pay_mkey,64,"\r\n");//转换为pem格式的私钥
$private_pem = "-----BEGIN PRIVATE KEY-----\r\n".$private_pem."-----END PRIVATE KEY-----\r\n";
//write_log("public_pem=".$public_pem);
ksort($data);
$noarr =array('Sign');
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if ( !in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val ===0 || $arr_val ==='0') ) {
		$signtext .= $arr_key.'='.$arr_val.'&';
	}
}
$signtext = substr($signtext,0,-1);
//write_log("signtext=".$signtext);
$PublicKey = openssl_get_publickey($public_pem);
if ($PublicKey == false) {
	echo "打开公钥出错";
	//write_log("打开公钥出错=".$PublicKey);
	exit;
}
$va = openssl_verify($signtext, $sign, $PublicKey, OPENSSL_ALGO_MD5);
if($va != 1) {
  echo "数据校验不通过";
  //write_log("数据校验不通过=".$va);
  exit;
}

#到账判断
if ($success_msg == $success_code) {
		$result_insert = update_online_money($order_no, $mymoney);
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
