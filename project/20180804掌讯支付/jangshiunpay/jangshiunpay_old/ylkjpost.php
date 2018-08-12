<?php
header("Content-type:text/html; charset=utf-8");
#第三方名稱 : 掌訊支付
#支付方式 : ylkj;
include_once("./addsign.php");
include_once("../moneyfunc.php");
include_once("../../../database/mysql.config.php");


$S_Name = $_REQUEST['S_Name'];
$top_uid = $_REQUEST['top_uid'];
$pay_type =$_REQUEST['pay_type'];
#获取第三方资料(非必要不更动)
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];//同步
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];//异步
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}


#固定参数设置
$form_url = 'http://www.leleec.com:8086/mpcctp/cashier/pay.ac';
$bank_code = $_REQUEST['bank_code'];
$order_no = getOrderNo();
$notify_url = $merchant_url;
$client_ip = getClientIp();
$pr_key = $pay_mkey;//私钥
$pu_key = $pay_account;//公钥
$order_time = date("YmdHis");


$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');
$MOAmount = number_format($_REQUEST['MOAmount']*100, 0, '.', '');
#第三方传值参数设置
$data = array(
"custId" => $pay_mid,
"custOrderNo" => $order_no,
"payAmt" => $MOAmount,
"backUrl" => $notify_url,
"version" => '2.1',
"orgNo" => $pu_key,
"tranType" => '0302',
"goodsName" => 'pay',
"sign" => array(
"str_arr" => array(
"backUrl" => $notify_url,
"custId" => $pay_mid,
"custOrderNo" => $order_no,
"goodsName" => "pay",
"orgNo" => $pu_key,
"payAmt" => $MOAmount,
"tranType" => "0302",
"version" => "2.1",
),
"mid_conn" => "=",
"last_conn" => "&",
"encrypt" => array(
"0" => "MD5",
),
"key_str" => "&key=",
"key" => $pr_key,
"havekey" => "1",
),
);
#变更参数设定
$payType = $pay_type."_ylkj";
$bankname = $pay_type."->银联快捷在线充值";
#新增至资料库，確認訂單有無重複， function在 moneyfunc.php裡(非必要不更动)
$result_insert = insert_online_order($S_Name , $order_no , $mymoney,$bankname,$payType,$top_uid);
if ($result_insert == -1){
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2){
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}


#签名排列，可自行组字串或使用http_build_query($array)
foreach ($data as $arr_key => $arr_value) {
  if (is_array($arr_value)) {
    $data[$arr_key] = sign_text($arr_value);
  }
}
foreach ($data as $arr_key => $arr_value) {
  $data_str .= $arr_key.'='.$arr_value.'&';
}
$data_str = substr($data_str,0,-1);


#curl获取响应值
$res = curl_post($form_url,$data_str,"POST");
$res = json_decode($res,1);
#跳转qrcode
$url = $res['busContent'];
if ($res['code'] == '000000') {
  echo $url;
}else{
  echo "错误码：".$res['code']."错误讯息：".$res['msg'];
  exit();
}
?>