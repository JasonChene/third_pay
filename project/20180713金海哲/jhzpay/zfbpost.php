<?php
header("Content-type:text/html; charset=utf-8");
// include_once("../../../database/mysql.config.php");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");
$top_uid = $_REQUEST['top_uid'];

if(function_exists("date_default_timezone_set"))
{
	date_default_timezone_set("Asia/Shanghai");
}
//获取第三方的资料
$params = array(':pay_type'=>$_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'].$row['zfb_returnUrl'];
$merchant_url = $row['pay_domain'].$row['zfb_synUrl'];
$pay_type = $row['pay_type'];
if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数[11]";
	exit;
}


$value = number_format($_REQUEST['MOAmount'], 2, '.', '');//订单金额
$notifyurl = $merchant_url; //商户异步通知地址
$returnUrl = $return_url;//服务器底层通知地址


$payUrl="http://zf.szjhzxxkj.com/ownPay/pay";


$public_key = $pay_account;
$private_key = $pay_mkey;
// 请求数据赋值
$data = array();

$data['merchantNo'] =  $pay_mid;
$data['requestNo'] =  getOrderNo(); //支付流水
$data['amount'] = number_format($_REQUEST['MOAmount']*100, 0, '.', '');//金额（分）
$data['payMethod'] = '6003';//业务代码
$data['backUrl'] = $notifyurl;   //服务器返回URL
$data['pageUrl'] = $returnurl;   //页面返回URL
$data['agencyCode'] = '';
$data['payDate'] = time();   //支付时间，必须为时间戳
$data['remark1'] = 'GOODS'; 
$data['remark2'] ='';
$data['remark3'] = '';

if (_is_mobile()) {
    $data["payMethod"] = "6008";
}

$signature=$pay_mid."|".$data['requestNo']."|".$data['amount']."|".$data['pageUrl']."|".$data['backUrl']."|".$data['payDate']."|".$data['agencyCode']."|".$data['remark1']."|".$data['remark2']."|".$data['remark3'];


$pr_key ='';
if(openssl_pkey_get_private($private_key)){
    $pr_key = openssl_pkey_get_private($private_key);
}else{
    exit;
}
$pu_key = '';
if(openssl_pkey_get_public($public_key)){
    $pu_key = openssl_pkey_get_public($public_key);
}else{
    exit;
}


$sign = '';

//openssl_sign(加密前的字符串,加密后的字符串,密钥:私钥);
openssl_sign($signature,$sign,$pr_key);


openssl_free_key($pr_key);

$sign = base64_encode($sign);

$data['signature'] = $sign;



$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($ch, CURLOPT_POST, TRUE); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
        curl_setopt($ch, CURLOPT_URL, $payUrl);
        $response =  curl_exec($ch);
        curl_close($ch);



$str = stripslashes($response);  

//转成Array
$arr = json_decode($str,true);  
//转成json
$php_json = json_encode($response);
//去掉返回字符串


//获取返回字符串sign
$resultsign=$arr['sign'];
//从arr去掉sign
unset($arr['sign']);
//去掉斜杠
$original_str=stripslashes(json_encode($arr));
$result=openssl_verify($original_str,base64_decode($resultsign),$public_key);




$bankname = $row['pay_type']."->支付宝在线充值";
$payType = $row['pay_type']."_zfb";

$result_insert = insert_online_order($_REQUEST['S_Name'] , $data['requestNo'] ,$value,$bankname,$payType,$top_uid);
	
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




if ( $result == "1" ) {
	if (_is_mobile()) {
		$jumpurl = stripslashes($arr['backQrCodeUrl']);
	}else {
		if(strstr($arr['backQrCodeUrl'],"&")){
            $code=str_replace("&", "aabbcc", $arr['backQrCodeUrl']);//有&换成aabbcc
        }else{
            $code=$arr['backQrCodeUrl'];
        }
        $jumpurl =('../qrcode/qrcode.php?type=zfb&code=' .$code);
	}
}else{
  echo $arr['msg'];
  exit;
}			

?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>