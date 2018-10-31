<?php
header("Content-type:text/html; charset=utf-8");
#第三方名稱 : 诚信通
include_once("./addsign.php");
include_once("../moneyfunc.php");
include_once("../../../database/mysql.config.php");//原数据库的连接方式
// include_once("../../../database/mysql.php");//现数据库的连接方式


$S_Name = $_REQUEST['S_Name'];
$top_uid = $_REQUEST['top_uid'];
$pay_type = $_REQUEST['pay_type'];
#获取第三方资料(非必要不更动)
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);//原数据库的连接方式
// $stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = trim($row['pay_domain'] . $row['wx_returnUrl']);//同步
$merchant_url = trim($row['pay_domain'] . $row['wx_synUrl']);//异步
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}


#固定参数设置
$form_url = 'https://www.longlong888.com/api/shopApi/order/createorder';
$bank_code = $_REQUEST['bank_code'];
$order_no = getOrderNo();
$notify_url = $merchant_url;
$client_ip = getClientIp();
$pr_key = $pay_mkey;//私钥
$pu_key = $pay_account;//公钥
$order_time = date("YmdHis");


$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');
$MOAmount = number_format($_REQUEST['MOAmount'], 2, '.', '');
#第三方传值参数设置
$data = array(
  "shop_id" => $pay_mid,
  "user_id" => $S_Name,
  "money" => $MOAmount,
  "type" => "alipay",//支付宝
  "shop_no" => $order_no,
  "notify_url" => $notify_url,
  "sign" => array(
    "str_arr" => array(
      "shop_id" => $pay_mid,
      "user_id" => $S_Name,
      "money" => $MOAmount,
      "type" => "alipay",//支付宝
    ),
    "mid_conn" => "",
    "last_conn" => "",
    "encrypt" => array(
      "0" => "MD5",
    ),
    "key_str" => "",
    "key" => $pr_key,
    "havekey" => "",
  ),
);
#变更参数设定
$payType = $pay_type . "_zfb";
$bankname = $pay_type . "->支付宝在线充值";
#新增至资料库，確認訂單有無重複， function在 moneyfunc.php裡(非必要不更动)
$result_insert = insert_online_order($S_Name, $order_no, $mymoney, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}


#签名排列，可自行组字串或使用http_build_query($array)
foreach ($data as $arr_key => $arr_value) {
  if (is_array($arr_value)) {
    $data[$arr_key] = sign_text($arr_value);
  }
}
#curl获取响应值
$res = httpspost($form_url, $data);
$row = json_decode($res, 1);

#跳转qrcode
if (isset($row['qrcode_url']) || isset($row['pay_url'])) {
  $url = $row['qrcode_url'];
  if (_is_mobile()) {
    $url = $row['pay_url'];
  }
  $jumpurl = $url;
} else {
  if (isset($row['errorCode']) || isset($row['message'])) {
    echo "错误码：" . $row['errorCode'] . "错误讯息：" . $row['message'];
  }
  exit();
}
echo '正在为您跳转中，请稍候......';
header('Location:' . $jumpurl);
exit();


function httpspost($url, $data)
{
  $curlData = json_encode($data);
  $curl = curl_init(); // 启动一个CURL会话
  curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
  // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
  // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
  // curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
  // curl_setopt($curl, CURLOPT_SSL_CIPHER_LIST, 'SSLv3');
  curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
  curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
  curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
  curl_setopt($curl, CURLOPT_POSTFIELDS, $curlData); // Post提交的数据包
  curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
  curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Content-Length:' . strlen($curlData)));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
  $tmpInfo = curl_exec($curl); // 执行操作
  if (curl_errno($curl)) {
    echo 'Errno ' . curl_error($curl);//捕抓异常
  }
  curl_close($curl); // 关闭CURL会话
  return $tmpInfo; // 返回数据，json格式
}
?>