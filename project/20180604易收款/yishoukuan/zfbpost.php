<?php
header("Content-type:text/html; charset=UTF8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');


if (function_exists("date_default_timezone_set")) {
	date_default_timezone_set("Asia/Shanghai");
}


//獲取第三方的资料
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商戶號
$pay_mkey = $row['mer_key'];//易收款公钥
$pay_account = $row['mer_account'];
$idArray = explode("###", $pay_account);
$merchNo = $idArray[0];//合作方ID
$public_key = $idArray[1];//合作方公钥
$private_key = $idArray[2];//合作方私钥
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];
$pay_type = $_REQUEST['pay_type'];
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}
function to_post($postUrl,$curlPost){
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, true);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    return $data;
}
function getRandom($param) {
	$str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$key = "";
	for ($i = 0;$i < $param;$i++) {
		$key.= $str{mt_rand(0, 32) };
	}
	return $key;
}
function encrypt(string $data, string $key, string $method){
    $ivSize = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($ivSize);

    $encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);

    // For storage/transmission, we simply concatenate the IV and cipher text
    $encrypted = base64_encode($iv . $encrypted);

    return $encrypted;
}
function pkcs5_pad ($text, $blocksize) {
	$pad = $blocksize - (strlen($text) % $blocksize);
	return $text . str_repeat(chr($pad), $pad);
}

function decrypt(string $data, string $key, string $method){
    $data = base64_decode($data);
    $ivSize = openssl_cipher_iv_length($method);
    $iv = substr($data, 0, $ivSize);
    $data = openssl_decrypt(substr($data, $ivSize), $method, $key, OPENSSL_RAW_DATA, $iv);

    return $data;
}

//參數設定
$scan = 'zfb';
$order_amount = number_format($_REQUEST['MOAmount'], 2, '.', '');
$order_no = getOrderNo();
$bankname = $pay_type . "->支付宝在线充值";
$payType = $pay_type . "_zfb";

// 確認訂單有無重複， function在 moneyfunc.php 裡
$result_insert = insert_online_order($_REQUEST['S_Name'], $order_no, $order_amount, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
	echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
	exit;
} else if ($result_insert == -2) {
	echo "订单号已存在，请返回支付页面重新支付";
	exit;
}

$paydata = array(
	'merchantCode' => $pay_mid,//商户编号
	'orderNumber' => $order_no,//订单号
	'tranCode' => '009',//接口类型,支付宝H5
	'totalAmount' => $order_amount,//订单金额
	'subject' => 'test',//传入公众号名称-实际商品名称，不能是中文
	'aisleType' => '2',//支付通道
	'PayType' => '0',//支付方式,支付宝H5
	'callback' => $merchant_url,//支付成功状态有异步通知
	'desc' => "test1",//对订单的描述
	'terminalId' => getClientIp(),//用户手机Ip
	'sceneType' => "2",//场景类型wap
	'wapName' => "zfbtest",//WAP网站名
	'successUrl' => '',//同步通知，测试不跳转，可传空值
);
$public_pem = chunk_split($public_key,64,"\r\n");//转换为pem格式的公钥
$public_pem = "-----BEGIN PUBLIC KEY-----\r\n".$public_pem."-----END PUBLIC KEY-----\r\n";
$private_pem = chunk_split($private_key,64,"\r\n");//转换为pem格式的私钥
$private_pem = "-----BEGIN PRIVATE KEY-----\r\n".$private_pem."-----END PRIVATE KEY-----\r\n";
$ku_pem = chunk_split($pay_mkey,64,"\r\n");//转换为pem格式的公钥
$ku_pem = "-----BEGIN PUBLIC KEY-----\r\n".$ku_pem."-----END PUBLIC KEY-----\r\n";
//生成AES秘钥
$cooperatorAESKey = getRandom(16);
//AES对称密钥cooperatorAESKey加密请求报文
$postdata['Context']  = encrypt(json_encode($paydata, JSON_UNESCAPED_UNICODE) , $cooperatorAESKey,'AES-128-ECB');
/*用合作方RSA私钥签名请求报文*/
$pri_key = openssl_get_privatekey($private_pem);
if ($pri_key == false) {
	echo "打开私钥出错";
	exit();
}
$sign = '';
$pub=openssl_sign(json_encode($postdata, JSON_UNESCAPED_UNICODE),$sign,$pri_key);
if ($pub){
	$postdata['signData'] = $sign = base64_encode($sign);
}else {
	echo "加密失敗";
	exit();
}
$postdata['agentId'] = urlencode($merchNo);//合作方ID
/*用易收款扫码支付平台RSA公钥加密合作方AES对称密钥cooperatorAESKey*/
$ku_Key = openssl_get_publickey($ku_pem);//获取公钥内容
if ($ku_Key == false) {
	echo "打开公钥出错";
	exit();
}
$r = openssl_public_encrypt($cooperatorAESKey, $encrypted, $ku_Key);//用openssl加密
if($r){
    $postdata['encrtpKey'] =  base64_encode($encrypted);//base64编码
}else {
	echo "加密失敗";
	exit();
}

$url = "http://api.coobpay.com/aggregate/kbpay/pay.kb";
$postdata2 = "Context=".urlencode($postdata['Context'])."&signData=".urlencode($postdata['signData'])."&encrtpKey=".urlencode($postdata['encrtpKey'])."&agentId=".$postdata['agentId'];
$res = json_decode(to_post($url,$postdata2),true);//提交數據
$conContext = $res['Context'];
$conEncrtptKey = base64_decode($res['encrtpKey']);

if(!$res){
	echo "res not exist";
}
//用平台方私钥解密AES秘钥
$privateKey = openssl_pkey_get_private($private_pem);//获取私钥内容
if ($privateKey == false) {
	echo "打开私钥出错";
	exit();
}
$r2 = openssl_private_decrypt($conEncrtptKey, $decrypted, $privateKey);
$decrypt = $decrypted;

//用$decrypted，解密$conContext
$contextArr = array();
if($r2){
	//然后用$decrypted，解密$conContext就好了
	$Context = decrypt($conContext , $decrypt,'AES-128-ECB');
	$contextArr = json_decode($Context, true);
}else {
	echo "解密失敗";
	exit();
}

if ($contextArr['respCode']=="555555") {
	header("location:" . $contextArr["pay_url"]);
}else {
	echo $contextArr['respMsg'];
}

?>
