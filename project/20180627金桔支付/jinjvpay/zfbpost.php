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
$top_uid = $_REQUEST['top_uid'];
 

////////////////////////////////////请求参数//////////////////////////////////////

if(function_exists("date_default_timezone_set"))
{
	date_default_timezone_set("Asia/Shanghai");
}

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
$pay_type = $row['pay_type'];
if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}
$orderno = date("YmdHis").substr(microtime(),2,5).rand(1,9);//流水号
$value = number_format($_REQUEST['MOAmount'],2,".","");//订单金额

$bankname = $pay_type."->支付宝在线充值";
$payType = $pay_type."_zfb";
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

	$url = "http://www.jinjupay.com/api/pay/orderPay";
	$data = array();
	$data['mechno'] = $pay_mid;//商户号，1118004517是测试商户号，线上发布时要更换商家自己的商户号！
	$data['orderip'] = getClientIp();
	$data['amount'] = $value*100;
	$data['body'] = "shopping";
	$data['extraparam'] = "shopping";
	$data['notifyurl'] = $merchant_url;
	$data['returl'] = $return_url;
	$data['orderno'] = $orderno;
	$data['payway'] = "ALIPAY";
	if(_is_mobile()){
	$data['paytype'] = "ALIPAY_WAP";
	}else{
	$data['paytype'] = "ALIPAY_SCAN_PAY";
	}
$temp='';
ksort($data);//对数组进行排序
//遍历数组进行字符串的拼接
foreach ($data as $x=>$x_value){
    if ($x_value != null){
        $temp = $temp.$x."=".$x_value."&";
    }
}
//echo $temp."key=".$pay_mkey;

	$data['sign']=strtoupper(md5($temp."key=".$pay_mkey));
// 
?>
</head>

<body onLoad="document.ddbill.submit();">
	<form name="ddbill" method="post" action="<?php echo $url; ?>">
<?php 
	
	foreach ($data as $key => $value) 
	{
		?>
			<input type="hidden" name="<?php echo $key ?>" value="<?php echo $value ?>" />
		<?php
	}
	
?>
		</form>
	</body>
</html>
