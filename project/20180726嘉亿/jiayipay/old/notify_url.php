<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
// include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

function decode($data,$private_content){   
	//读取秘钥
   $pr_key = openssl_pkey_get_private($private_content);
	   
   if ($pr_key == false){
	//    write_log("打开密钥出错");
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
   return $crypto;
}
function callback_to_array($json,$key){
	$array = json_decode($json,true);	
	$sign_string = $array['sign'];
	ksort($array);
	$sign_array = array();
	foreach ($array as $k => $v) {
		if ($k !== 'sign'){
			$sign_array[$k] = $v;
		}
	}
	$md5 =  strtoupper(md5(json_encode_ex($sign_array) . $key));
	if ($md5 == $sign_string){
		return $sign_array;
	}else{
		$result = array();
		$result['payResult'] = '99';
		$result['msg'] = '返回签名验证失败';
		return $result;
	}

}
function json_encode_ex($value){
	if (version_compare(PHP_VERSION,'5.4.0','<')){
	 $str = json_encode($value);
	 $str = preg_replace_callback("#\\\u([0-9a-f]{4})#i","replace_unicode_escape_sequence",$str);
	 $str = stripslashes($str);
	 return $str;
   }else{
	 return json_encode($value,320);
   }
  }
// write_log("notify");
#接收资料
#POST方法
$datakey = array();
foreach ($_POST as $key => $value) {
	$datakey[$key] = $value;
	// write_log($key."=".$value);
}

#获取该订单的支付名称
$params = array(':pay_type' => "嘉亿");
$sql = "select * from pay_set where pay_type=:pay_type";
// $stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$idArray = explode("###", $pay_mkey);
$md5key = $idArray[0];//合作方秘钥
$private_key = $idArray[1];//合作方公钥
$pay_account = $payInfo['mer_account'];
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	//write_log("非法提交参数");
	exit;
}
$public_pem = chunk_split($pay_account,64,"\r\n");//转换为pem格式的公钥
$public_pem = "-----BEGIN PUBLIC KEY-----\r\n".$public_pem."-----END PUBLIC KEY-----\r\n";
$private_pem = chunk_split($private_key,64,"\r\n");//转换为pem格式的私钥
$private_pem = "-----BEGIN PRIVATE KEY-----\r\n".$private_pem."-----END PRIVATE KEY-----\r\n";

//解密
$data = decode($datakey['data'],$private_pem);

//效验 sign 签名
$rows = callback_to_array($data, $md5key);


#到账判断
if ($rows['payResult']='00') {
		$result_insert = update_online_money($order_no, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			// write_log("会员信息不存在，无法入账");
			exit;
		}else if($result_insert == 0){
			echo ("0");
			// write_log("0".'at 0');
			exit;
		}else if($result_insert == -2){
			echo ("数据库操作失败");
			// write_log("数据库操作失败");
			exit;
		}else if($result_insert == 1){
			echo ("0");
			// write_log("0".'at 1');
			exit;
		} else {
			echo ("支付失败");
			// write_log("支付失败");
			exit;
		}
}else{
	echo "错误代码：" . $rows['payResult'] . ' 错误描述:' . $rows['msg'];
	// write_log("错误代码：" . $rows['payResult'] . ' 错误描述:' . $rows['msg']);
	exit;
}

?>
