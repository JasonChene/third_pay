<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");


function decrypt(string $data, string $key, string $method){
    $data = base64_decode($data);
    $ivSize = openssl_cipher_iv_length($method);
    $iv = substr($data, 0, $ivSize);
    $data = openssl_decrypt(substr($data, $ivSize), $method, $key, OPENSSL_RAW_DATA, $iv);

    return $data;
}
$data = array();
foreach ($_POST as $key => $value) {
	$data[$key] = $value;
	//write_log($key."=".$value);
}

$conContext = $data['Context'];
//write_log("conContext=".$conContext);
$conEncryptKey = base64_decode($data['encrtpKey']);
//write_log("conEncryptKey=".$conEncryptKey);


$params = array(':pay_name'=>"易收款");
$sql = "select * from pay_set where pay_name=:pay_name";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];
$idArray = explode("###", $pay_account);
$merchNo = $idArray[0];//合作方ID
$public_key = $idArray[1];//合作方公钥
$private_key = $idArray[2];//合作方私钥
if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}

$public_pem = chunk_split($public_key,64,"\r\n");//转换为pem格式的公钥
$public_pem = "-----BEGIN PUBLIC KEY-----\r\n".$public_pem."-----END PUBLIC KEY-----\r\n";
$private_pem = chunk_split($private_key,64,"\r\n");//转换为pem格式的私钥
$private_pem = "-----BEGIN RSA PRIVATE KEY-----\r\n".$private_pem."-----END RSA PRIVATE KEY-----\r\n";
$ku_pem = chunk_split($pay_mkey,64,"\r\n");//转换为pem格式的公钥
$ku_pem = "-----BEGIN PUBLIC KEY-----\r\n".$ku_pem."-----END PUBLIC KEY-----\r\n";
$privateKey = openssl_pkey_get_private($private_pem);//获取私钥内容
//write_log("privateKey=".$privateKey);
if ($privateKey==false) {
	echo "打开私钥出错";
	exit();
}
$r2 = openssl_private_decrypt($conEncryptKey, $decrypted, $privateKey);
//write_log("r2=".$r2);
$decrypt = $decrypted;
//write_log("decrypt=".$decrypt);
$Contextarr= array();
if($r2){
	$Context = decrypt($conContext , $decrypt,"AES-128-ECB");
	$Contextarr = json_decode($Context, true);
	//write_log("Context=".$Context);
}else {
	echo "解密失敗";
	exit();
}
if ($Contextarr['respType'] == "S" && $Contextarr['respCode'] == "000000"){
		$mymoney= number_format($Contextarr['buyerPayAmount'],2,'.','');
		$result_insert = update_online_money($Contextarr['orderNumber'],$mymoney);
		if ($result_insert==-1) {
			echo ("会员信息不存在，无法入账");
			exit;
		} elseif ($result_insert==0) {
			echo "000000";
			exit;
		} elseif ($result_insert==-2) {
			echo ("数据库操作失败");
			exit;
		} elseif ($result_insert==1) {
			echo "000000";
			exit;
		} else {
			echo("交易失败！");
		}
}else{
	echo("交易状态失败！");
}

?>
