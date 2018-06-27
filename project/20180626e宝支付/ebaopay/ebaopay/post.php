<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>充值接口-提交信息处理</title>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
$top_uid = $_REQUEST['top_uid'];
if (function_exists("date_default_timezone_set")) {
	date_default_timezone_set("Asia/Shanghai");
}
//获取第三方的资料
$params = array(':pay_type' => $_REQUEST['pay_type']);
// $sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl,t.mer_terid from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_terid = $row['mer_terid'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];
$pay_type = $row['pay_type'];
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}
$orderno = date("YmdHis") . substr(microtime(), 2, 5) . rand(1, 9);//流水号
$paytype = 20; //20代表网银支付，22代表支付宝支付，30代表微信支付
$paycode = "";
$usercode = $pay_mid;//商户号
$value = round($_REQUEST['MOAmount'], 2);//订单金额
$notifyurl = $merchant_url; //商户异步通知地址
$returnUrl = $return_url;//服务器底层通知地址
$dateis = date('is');
$remark = $dateis . md5($pay_mid . $dateis);//订单附加消息
$datetime = date("YmdHis", time());//交易时间
$goodsname = "GOODS NAME";//产品名称

$Md5key = $pay_mkey;//md5密钥（KEY）

//MD5签名格式
$Signature = md5("usercode=" . $usercode . "&orderno=" . $orderno . "&datetime=" . $datetime . "&paytype=" . $paytype . "&value=" . $value . "&notifyurl=" . $notifyurl . "&returnurl=" . $returnUrl . "&key=" . $Md5key);

$payUrl = "http://support.amxmy.top/api.html#pay";

$bankname = $pay_type . "->网银在线充值";
$payType = $pay_type . "_wy";
$result_insert = insert_online_order($_REQUEST['S_Name'], $orderno, $value, $bankname, $payType, $top_uid);

if ($result_insert == -1) {
	echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
	exit;
} else if ($result_insert == -2) {
	echo "订单号已存在，请返回支付页面重新支付";
	exit;
}

// $apiurl = "http://insert.maoloy.cn/Online/GateWay";/*接口提交地址*/
$apiurl = "http://sapi.eboopay.com/Pay_Index.html";/*接口提交地址*/
$version = "3.0";/*接口版本号,目前固定值为3.0*/
$method = "Rx.online.pay";/*接口名称: Rx.online.pay*/
$partner = $pay_mid;//商户id,由API分配
$banktype = "WEIXIN";//银行类型 default为跳转到接口进行选择支付
$paymoney = $value;//单位元（人民币）,两位小数点
$ordernumber = $orderno;//商户系统订单号，该订单号将作为接口的返回数据。该值需在商户系统内唯一
$callbackurl = $notifyurl;//下行异步通知的地址，需要以http://开头且没有任何参数
$hrefbackurl = $returnUrl;//下行同步通知过程的返回地址(在支付完成后接口将会跳转到的商户系统连接地址)。注：若提交值无该参数，或者该参数值为空，则在支付完成后，接口将不会跳转到商户系统，用户将停留在接口系统提示支付成功的页面。
$goodsname = "在线支付";//商品名称。若该值包含中文，请注意编码
$attach = $Md5key;//备注信息，下行中会原样返回。若该值包含中文，请注意编码
$isshow = "1";//该参数为支付宝扫码、微信、QQ钱包专用，默认为1，跳转到网关页面进行扫码，如设为0，则网关只返回二维码图片地址供用户自行调用
$key = $pay_mkey;//商户Key,由API分配
$signSource = sprintf("version=%s&method=%s&partner=%s&banktype=%s&paymoney=%s&ordernumber=%s&callbackurl=%s%s", $version, $method, $partner, $banktype, $paymoney, $ordernumber, $callbackurl, $key);
$sign = md5($signSource);//32位小写MD5签名值，UTF-8编码
// echo "111==" . $sign;
// exit;
$postUrl = $apiurl . "?version=" . $version;
$postUrl .= "&method=" . $method;
$postUrl .= "&partner=" . $partner;
$postUrl .= "&banktype=" . $banktype;
$postUrl .= "&paymoney=" . $paymoney;
$postUrl .= "&ordernumber=" . $ordernumber;
$postUrl .= "&callbackurl=" . $callbackurl;
$postUrl .= "&hrefbackurl=" . $hrefbackurl;
$postUrl .= "&goodsname=" . $goodsname;
$postUrl .= "&attach=" . $attach;
$postUrl .= "&isshow=" . $isshow;
$postUrl .= "&sign=" . $sign;

//打印
echo '<pre>';
echo ('<br> signSource = <br>');
var_dump($signSource);
echo ('<br> postUrl = <br>');
var_dump($postUrl);
// echo ('<br><br> row = <br>');
// var_dump($row);
echo '</pre>';

// exit;


header("location:$postUrl");



?>
</head>

</html>
