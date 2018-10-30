<?php
header("Content-type:text/html; charset=utf-8");
// include_once("../../../database/mysql.config.php");//原数据库的连接方式
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
	date_default_timezone_set("Asia/Shanghai");
}

#function
function curl_post($url, $data)
{ #POST访问
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec($ch);
	if (curl_errno($ch)) {
		return curl_error($ch);
	}
	return $tmpInfo;
}

function payType_bankname($scan, $pay_type)
{
	global $payType, $bankname;
	if ($scan == "wy") {
		$payType = $pay_type . "_wy";
		$bankname = $pay_type . "->网银在线充值";
	} elseif ($scan == "yl" || $scan == "ylfs") {
		$payType = $pay_type . "_yl";
		$bankname = $pay_type . "->银联钱包在线充值";
	} elseif ($scan == "qq" || $scan == "qqfs") {
		$payType = $pay_type . "_qq";
		$bankname = $pay_type . "->QQ钱包在线充值";
	} elseif ($scan == "wx" || $scan == "wxfs") {
		$payType = $pay_type . "_wx";
		$bankname = $pay_type . "->微信在线充值";
	} elseif ($scan == "zfb" || $scan == "zfbfs") {
		$payType = $pay_type . "_zfb";
		$bankname = $pay_type . "->支付宝在线充值";
	} elseif ($scan == "jd" || $scan == "jdfs") {
		$payType = $pay_type . "_jd";
		$bankname = $pay_type . "->京东钱包在线充值";
	} elseif ($scan == "ylkj") {
		$payType = $pay_type . "_ylkj";
		$bankname = $pay_type . "->银联快捷在线充值";
	} elseif ($scan == "bd" || $scan == "bdfs") {
		$payType = $pay_type . "_bd";
		$bankname = $pay_type . "->百度钱包在线充值";
	} else {
		echo ('payType_bankname出错啦！');
		exit;
	}
}

function QRcodeUrl($code)
{ #替换QRcodeUrl中&符号
	if (strstr($code, "&")) {
		$code2 = str_replace("&", "aabbcc", $code);//有&换成aabbcc
	} else {
		$code2 = $code;
	}
	return $code2;
}

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);//原数据库的连接方式
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商戶私钥
$pay_account = $row['mer_account'];
$pay_account_arr = explode("###", $pay_account);
$return_url = trim($row['pay_domain']) . trim($row['wx_returnUrl']);//return跳转地址
$merchant_url = trim($row['pay_domain']) . trim($row['wx_synUrl']);//notify回传地址
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}
#固定参数设置
$top_uid = $_REQUEST['top_uid'];
$order_no = getOrderNo();
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');

#第三方参数设置
$post_data = array(
	"version" => 'V2.1', //版本号
	"merNo" => $pay_mid, //商户号
	"payData" => '', //请求数据
);

$payDataJSON = array(
	"payType" => '', //支付类型
	"returnUrl" => $return_url, //同步跳转地址
	"notifyUrl" => $merchant_url, //异步通知地址
	"orderNo" => $order_no, //商户订单号
	"webSite" => $pay_account_arr[0], //购物网站
	"orderAmount" => number_format($_REQUEST['MOAmount'], 2, '.', ''), //交易总金额
	"goodsInfo" => 'pay', //商品详情
	"ip" => getClientIp(), //持卡人 IP 地址
	"email" => 'tech@qq.com', //持卡人邮箱
	// "signInfo" => '', //签名
	"remark" => 'pay', //传入交易备注
);

#变更参数设置
$form_url = 'http://58.82.232.169/gateway/payin/pay';//wap提交地址
$scan = 'zfb';
$payDataJSON['payType'] = 'ali_h5';//支付宝H5
payType_bankname($scan, $pay_type);

#新增至资料库，確認訂單有無重複， function在 moneyfunc.php裡(非必要不更动)
$result_insert = insert_online_order($_REQUEST['S_Name'], $order_no, $mymoney, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
	echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
	exit;
} else if ($result_insert == -2) {
	echo "订单号已存在，请返回支付页面重新支付";
	exit;
}

#签名排列，可自行组字串或使用http_build_query($array)
$public_pem = chunk_split($pay_account_arr[1], 64, "\r\n");//转换为pem格式的公钥
$public_pem = "-----BEGIN PUBLIC KEY-----\r\n" . $public_pem . "-----END PUBLIC KEY-----\r\n";
$private_pem = chunk_split($pay_mkey, 64, "\r\n");//转换为pem格式的私钥
$private_pem = "-----BEGIN RSA PRIVATE KEY-----\r\n" . $private_pem . "-----END RSA PRIVATE KEY-----\r\n";

ksort($payDataJSON);
//生成签名原串
$signInfo = "";
foreach ($payDataJSON as $key => $val) {
	if ($signInfo != "") {
		$signInfo = $signInfo . "&";
	}
	$signInfo = $signInfo . $key . "=" . $val;
}
//实例化签名类
$rsaUtils = new RSAUtils();
//在payDataJSON中加入签名
$payDataJSON['signInfo'] = $rsaUtils->md5Sign(str_replace("\/", "/", $signInfo), $private_pem);
//使用平台公钥 将$payDataJSON进行 RSA公钥加密 后得到payData
$payData = $rsaUtils->publicEncrypt("/platform_public_key.pem", json_encode($payDataJSON), $public_pem);
$post_data['payData'] = $payData;
// $http_resp_content_encrypt = json_decode(send_post($form_url, $post_data));
$http_resp_content_encrypt = json_decode(send_post($form_url, $post_data), 1);
$resp_payData = $http_resp_content_encrypt['payData'];
$resp_payData = $rsaUtils->privateDecrypt($private_pem, $resp_payData, $private_pem);
$resp_payData_array;
foreach (json_decode($resp_payData, 1) as $key => $val) {
	if ($key == "signInfo") {
		continue;
	}
	$resp_payData_array[$key] = $val;
}
ksort($resp_payData_array);
$signInfo_str = "";
foreach ($resp_payData_array as $key => $val) {
	if ($signInfo_str != "") {
		$signInfo_str = $signInfo_str . "&";
	}
	$signInfo_str = $signInfo_str . $key . "=" . $val;
}
$resp_signInfo = json_decode($resp_payData, 1)['signInfo'];
$row = json_decode($resp_payData, 1);
$sign_correct = $rsaUtils->signIsValid($public_pem, $signInfo_str, $resp_signInfo);

#跳转
if ($http_resp_content_encrypt['retCode'] != 1) {
	echo '错误代码:' . $http_resp_content_encrypt['retCode'] . "<br>";
	echo '错误讯息:' . $http_resp_content_encrypt['errMsg'] . "<br>";
	exit;
} else if ($sign_correct != 1) {
	echo '错误讯息:响应验签错误' . "<br>";
	exit;
} else if (!isset($row['url'])) {
	echo '错误讯息:无支付url' . "<br>";
	exit;
} else {
	$qrcodeUrl = $row['url'];
	if (_is_mobile()) {
		$jumpurl = $qrcodeUrl;
	} else {
		$jumpurl = '../qrcode/qrcode.php?type=' . $scan . '&code=' . urlencode($qrcodeUrl);
	}
}

#跳轉方法
?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl ?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
      <?php
					if (isset($form_data)) {
						foreach ($form_data as $arr_key => $arr_value) {
							?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php 
				}
			} ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>

<?php
//第三方提供function

/**
 * 发送post请求
 * @param string $url 请求地址
 * @param array $post_data post键值对数据
 * @return string
 */
function send_post($url, $post_data)
{
	$postdata = http_build_query($post_data);
	$options = array(
		'http' => array(
			'method' => 'POST',
			'header' => 'Content-type:application/x-www-form-urlencoded',
			'content' => $postdata,
			'timeout' => 15 * 60 // 超时时间（单位:s）
		)
	);
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	return $result;
}

class RSAUtils
{

	/**
	 * RSA公钥解密(私钥加密的内容通过公钥可以解密出来)
	 * @param string $public_key 公钥
	 * @param string $data 私钥加密后的字符串
	 * @return string $decrypted 返回解密后的字符串
	 * @author mosishu
	 */
	public function publicDecrypt($public_key_url, $data)
	{
		$public_key = file_get_contents(dirname(__FILE__) . $public_key_url);
		$decrypted = '';
		$pu_key = openssl_pkey_get_public($public_key);//这个函数可用来判断公钥是否是可用的
		$plainData = str_split(base64_decode($data), 128);//生成密钥位数 1024 bit key
		foreach ($plainData as $chunk) {
			$str = '';
			$decryptionOk = openssl_public_decrypt($chunk, $str, $pu_key);//公钥解密
			if ($decryptionOk === false) {
				return false;
			}
			$decrypted .= $str;
		}
		return $decrypted;
	}

	/**
	 * RSA私钥加密
	 * @param string $private_key 私钥
	 * @param string $data 要加密的字符串
	 * @return string $encrypted 返回加密后的字符串
	 * @author mosishu
	 */
	public function privateEncrypt($private_key_url, $data)
	{
		$private_key = file_get_contents(dirname(__FILE__) . $private_key_url);
		$encrypted = '';
		$pi_key = openssl_pkey_get_private($private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        //最大允许加密长度为117，得分段加密
		$plainData = str_split($data, 100);//生成密钥位数 1024 bit key
		foreach ($plainData as $chunk) {
			$partialEncrypted = '';
			$encryptionOk = openssl_private_encrypt($chunk, $partialEncrypted, $pi_key);//私钥加密
			if ($encryptionOk === false) {
				return false;
			}
			$encrypted .= $partialEncrypted;
		}
		$encrypted = base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
		return $encrypted;
	}

	/**
	 * 公钥钥加密
	 * @param string $data
	 * @return bool|string
	 */
	public
		function publicEncrypt($public_key_url, $data, $public_key)
	{
		// $public_key = file_get_contents(dirname(__FILE__) . $public_key_url);
		$encrypted = '';
		$pu_key = openssl_pkey_get_public($public_key);
        //PHP加密长度限制,需进行分割加密后再重新拼装
		$plainData = str_split($data, 100);
		foreach ($plainData as $chunk) {
			$partialEncrypted = '';
			$encryptionOk = openssl_public_encrypt($chunk, $partialEncrypted, $pu_key);//公钥加密
			if ($encryptionOk === false) {
				return false;
			}
			$encrypted .= $partialEncrypted;
		}
		$encrypted = base64_encode($encrypted);
		return $encrypted;
	}

	/**
	 * 私钥解密
	 * @param $data
	 * @return bool|string
	 */
	public function privateDecrypt($private_key_url, $data, $private_key)
	{
		// $private_key = file_get_contents(dirname(__FILE__) . $private_key_url);
		$decrypted = '';
		$pi_key = openssl_pkey_get_private($private_key);
		$plainData = str_split(base64_decode($data), 128);
		foreach ($plainData as $chunk) {
			$str = '';
			$decryptionOk = openssl_private_decrypt($chunk, $str, $pi_key);//私钥解密
			if ($decryptionOk === false) {
				return false;
			}
			$decrypted .= $str;
		}
		return $decrypted;
	}

	/**
	 * 私钥生成数字签名
	 * @param $data 待签数据
	 * @return String 返回签名
	 */
	public
		function md5Sign($data = '', $private_key)
	{
		if (empty($data)) {
			return false;
		}
		// $private_key = file_get_contents(dirname(__FILE__) . '/mer_private_key.pem');
		if (empty($private_key)) {
			echo ");Private Key error!";
			return false;
		}

		$pkeyid = openssl_get_privatekey($private_key);
		if (empty($pkeyid)) {
			echo "private key resource identifier False!";
			return false;
		}

		$verify = openssl_sign($data, $signature, $pkeyid, OPENSSL_ALGO_MD5);
		openssl_free_key($pkeyid);
		return base64_encode($signature);
	}

	/**
	 * 利用公钥和数字签名验证合法性
	 * @param $public_key_url 公钥地址
	 * @param $data 待验证数据
	 * @param $signature 数字签名
	 * @return -1:error验证错误 1:correct验证成功 0:incorrect验证失败
	 */
	public
		function signIsValid($public_key, $data = '', $signature = '')
	{
		if (empty($data) || empty($signature)) {
			return false;
		}

		// $public_key = file_get_contents(dirname(__FILE__) . $public_key_url);
		if (empty($public_key)) {
			echo "Public Key error!";
			return false;
		}

		$pkeyid = openssl_get_publickey($public_key);
		if (empty($pkeyid)) {
			echo "public key resource identifier False!";
			return false;
		}

		$ret = openssl_verify($data, base64_decode($signature), $pkeyid, OPENSSL_ALGO_MD5);
		switch ($ret) {
			case -1:
				echo "error";
				break;
			default:
				// echo $ret == 1 ? "correct" : "incorrect";//0:incorrect
				break;
		}
		return $ret;
	}


}

?>

