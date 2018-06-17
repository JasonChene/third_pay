<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>充值接口-提交信息处理</title>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
require_once "utils.php";
$top_uid = $_REQUEST['top_uid'];

$ylscan = false;
if(strstr($_REQUEST['pay_type'], "银联钱包"))
{
    $ylscan = true;
}
$ylkjscan = false;
if(strstr($_REQUEST['pay_type'], "银联快捷"))
{
    $ylkjscan = true;
}
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
$return_url = $row['pay_domain'].$row['wy_returnUrl'];
$merchant_url = $row['pay_domain'].$row['wy_synUrl'];
$pay_type = $row['pay_type'];
if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}
$orderno = date("YmdHis").substr(microtime(),2,5).rand(1,9);//流水号

$value = number_format($_REQUEST['MOAmount'],2,".","");//订单金额

if($ylscan){
$bankname = $pay_type."->银联钱包在线充值";
$payType = $pay_type."_yl";
}else if($ylkjscan){
$bankname = $pay_type."->银联快捷在线充值";
$payType = $pay_type."_ylkj";
}else{
$bankname = $pay_type."->网银在线充值";
$payType = $pay_type."_wy";
}
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

	
$branch_id = $pay_mid;//商户号，1118004517是测试商户号，线上发布时要更换商家自己的商户号！
$total_fee = $value * 100;
$url = "https://api.yingyupay.com:31006/yypay";
$post_data = array(
        'out_trade_no'      => $orderno,
        'back_notify_url'   => $merchant_url,
        'front_notify_url'  => $return_url,
        'branch_id'         => $pay_mid,
        'prod_name'         => "asd",
        'prod_desc'         => "jash",
        'total_fee'         => $total_fee,
        'nonce_str'         => createNoncestr(32)
);
if($ylscan){
	$post_data['messageid'] = "200001";
    $post_data['pay_type']  = "70";
}else if($ylkjscan){
$post_data['messageid']='200004';
$post_data['pay_type']= "65";
$post_data['front_notify_url']= $return_url;
}else{
	$post_data['messageid'] = "200002";
	$post_data['bank_code'] = $_REQUEST['bank_code'];
	//$post_data['bank_code'] = "ICBCD";
	$post_data['bank_flag'] = "0";
    $post_data['pay_type']  = "30";
}
//$reqData = sign($post_data, $pay_mkey);

$temp='';
ksort($post_data);//对数组进行排序
//遍历数组进行字符串的拼接
foreach ($post_data as $x=>$x_value){
    if ($x_value != null){
        $temp = $temp.$x."=".$x_value."&";
    }
}
$post_data['sign']=strtoupper(md5($temp."key=".$pay_mkey));
//var_dump($post_data);
$result = httpPost($url, $post_data);
if($ylscan||$ylkjscan){
	//var_dump($result);
    $resultJson=json_decode($result);
    if ($resultJson->resultCode == '00' && $resultJson->resCode == '00') {
		$pay_params	= $resultJson ->payUrl;	
		if($ylkjscan){
		header("location:" . $pay_params);
		}else{
		header("location:" . '../qrcode/qrcode.php?type=yl&code='.$pay_params);
		}
}
}else{//var_dump($result);
echo iconv("GBK", "UTF-8",$result);
}


?>
</head>

</html>