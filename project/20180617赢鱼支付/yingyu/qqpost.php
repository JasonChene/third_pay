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
include_once("utils.php");
$top_uid = $_REQUEST['top_uid'];


$getwximg = false;

////////////////////////////////////请求参数//////////////////////////////////////


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
$value = number_format($_REQUEST['MOAmount'],2,".","");//订单金额
$notifyurl = $merchant_url; //商户异步通知地址
$returnUrl = $return_url;//服务器底层通知地址

$payUrl="https://api.yingyupay.com:31006/yypay";//借贷混合


		$bankname = $row['pay_type']."->qq钱包在线充值";
		$payType = $row['pay_type']."_qq";
       
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

    $url = $payUrl;
    $post_data = array(
        'messageid'         => '200001',
        'out_trade_no'      => $orderno,
        'branch_id'         => $pay_mid,
        'pay_type'          => "50",
        'total_fee'         => $value * 100,
        'prod_name'         => 'aaa',
        'prod_desc'         => 'aaa',
        'back_notify_url'   => $merchant_url,
        'nonce_str'         => createNoncestr(16)
    );
if(_is_mobile()){
$post_data['messageid']='200004';
$post_data['pay_type']= "63";
$post_data['front_notify_url']= $return_url;
}

//echo $post_data['messageid'];exit;
    $reqData = sign($post_data, $pay_mkey);
    $result = httpPost($url, $reqData);
	//var_dump($result);
    $resultJson=json_decode($result);
    if ($resultJson->resultCode == '00' && $resultJson->resCode == '00') {
		$pay_params	= $resultJson ->payUrl;	
		if(_is_mobile()&&!$jdscan){
		header("location:" . $pay_params);
		}else{
		$pay_params = str_replace('&', 'aabbcc',htmlspecialchars_decode($pay_params));
        header("location:" . '../qrcode/qrcode.php?type=qq&code='.$pay_params);
		}
    } 
 
?>
