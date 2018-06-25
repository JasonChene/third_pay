<? header("content-Type: text/html; charset=utf-8");?>
<?php
/* *
 *功能：即时到账交易接口接入页
 *版本：3.0
 *日期：2013-08-01
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,
 *并非一定要使用该代码。该代码仅供学习和研究智付接口使用，仅为提供一个参考。
 **/
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
include('phpqrcode.php');
$top_uid = $_REQUEST['top_uid'];

$getwximg = false;

////////////////////////////////////请求参数//////////////////////////////////////

function get_client_ip() {
	$ip = $_SERVER['REMOTE_ADDR'];
		
	if (isset($_SERVER['HTTP_X_REAL_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_REAL_FORWARDED_FOR'];
	} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
		
	return $ip;
}

if(function_exists("date_default_timezone_set"))
{
	date_default_timezone_set("Asia/Shanghai");
}
$pay_type = $_REQUEST['pay_type'];
//获取第三方的资料
$params = array(':pay_type'=>$_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'].$row['wx_returnUrl'];
$merchant_url = $row['pay_domain'].$row['wx_synUrl'];
if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}
$orderno = date("YmdHis").substr(microtime(),2,5).rand(1,9);//流水号
$paytype = 20; //20代表网银支付，22代表支付宝支付，30代表微信支付
$paycode = "";
$usercode = $pay_mid;//商户号
$value = round($_REQUEST['MOAmount'],2);//订单金额
$notifyurl = $merchant_url; //商户异步通知地址
$returnUrl = $return_url;//服务器底层通知地址
$dateis = date('is');
$remark = $dateis.md5($pay_mid.$dateis);//订单附加消息
$datetime = date("YmdHis",time());//交易时间
$goodsname = "shopping";//产品名称

$Md5key=$pay_mkey;//md5密钥（KEY）
	
//MD5签名格式
$Signature=md5("usercode=".$usercode."&orderno=".$orderno."&datetime=".$datetime."&paytype=".$paytype."&value=".$value."&notifyurl=".$notifyurl."&returnurl=".$returnUrl."&key=".$Md5key);

$payUrl="https://gw.555pay.com/native/com.opentech.cloud.pay.trade.create/1.0.0";//借贷混合

$bankname = $pay_type."->QQ钱包在线充值";
$payType = $pay_type."_qq";
$result_insert = insert_online_order($_REQUEST['S_Name'] , $orderno , $value,$bankname,$payType,$top_uid);
	
if ($result_insert == -1)
{
	echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
	exit;
}
else if ($result_insert == -2)
{
	echo "订单号已存在，请返回支付页面重新支付";
	exit;
}

    $partner = $pay_mid;//商户号，1118004517是测试商户号，线上发布时要更换商家自己的商户号！
    $banktype = "TENPAY";
    if (_is_mobile()){
        $banktype = "TENWAPPAY";
    }
	$paymoney = $value;
	$ordernumber = $orderno;
	$callbackurl = $notifyurl;
	$hrefbackurl = $notifyurl;
    $attach = $goodsname;
$post_data = array(
    'partner' => $partner,
    'banktype' =>$banktype,
    'paymoney'=> $paymoney,
    'ordernumber' => $ordernumber,
    'callbackurl' => $callbackurl
);
$temp='';
//ksort($post_data);//对数组进行排序
//遍历数组进行字符串的拼接
foreach ($post_data as $x=>$x_value){
    if ($x_value != null){
        $temp = $temp.$x."=".iconv('UTF-8','GBK//IGNORE',$x_value)."&";
    }
}
$temp = substr($temp, 0, -1);
$sign=md5($temp.$pay_mkey);
//$reveiveData = $temp.'sign'.'='.$sign;
//echo $reveiveData;
$post_datas=array_merge($post_data,array('sign'=>$sign));
// 
?>
</head>

<body onLoad="document.ddbill.submit();">
	<form name="ddbill" method="get" action="https://pay.antopay.com/antoPay.html">
    <input type="hidden" name="banktype" value="<?php echo $post_datas['banktype']; ?>" />
    <input type="hidden" name="partner" value="<?php echo $post_datas['partner']; ?>" />
    <input type="hidden" name="paymoney" value="<?php echo $post_datas['paymoney']; ?>" />
    <input type="hidden" name="ordernumber" value="<?php echo $post_datas['ordernumber']; ?>" />
    <input type="hidden" name="callbackurl" value="<?php echo $post_datas['callbackurl']; ?>" />
    <input type="hidden" name="hrefbackurl" value="<?php echo $hrefbackurl; ?>" />
    <input type="hidden" name="attach" value="<?php echo $attach; ?>" />
    <input type="hidden" name="sign" value="<?php echo $post_datas['sign']; ?>" />
		</form>
	</body>
</html>