<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}
#function
function curl_post($url,$data){ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
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
function QRcodeUrl($code){
  if(strstr($code,"&")){
    $code2=str_replace("&", "aabbcc", $code);//有&换成aabbcc
  }else{
    $code2=$code;
  }
  return $code2;
}
function sign($plainText, $key){
    $plainText = json_encode($plainText);
    $resource = openssl_pkey_get_private($key);
    $result = openssl_sign($plainText, $sign, $resource);
    openssl_free_key($resource);
    return base64_encode($sign);
}
function parse_result($result){
    $arr = array();
    $response = urldecode($result);
    $arrStr = explode('&', $response);
    foreach ($arrStr as $str) {
        $p = strpos($str, "=");
        $key = substr($str, 0, $p);
        $value = substr($str, $p + 1);
        $arr[$key] = $value;
    }

    return $arr;
}
#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商戶私钥
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];//return跳转地址
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];//notify回传地址
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}
#固定参数设置
$top_uid = $_REQUEST['top_uid'];
$order_no = getOrderNo();
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');
//算出订单金额
$money = number_format($_REQUEST['MOAmount'], 0, '.', '');
$str_money = '000000000000';
$str_money = substr($str_money,0,-strlen($money));
$str_money = $str_money.$money;
#第三方参数设置
$data = array(
  "head" => array(
    "version" => '1.0',//版本号
    "method" => 'sandpay.trade.pay',//接口名称 统一下单并支付 sandpay.trade.barpay  预下单 sandpay.trade.precreate
    "productId" => '',//产品编码
    "accessType" => '1',//接入类型 1-普通商户接入 2-平台商户接入
    "mid" => $pay_mid, //商户 ID
    "channelType" => '07',//渠道类型 07-互联网 08-移动端
    "reqTime" => date('YmdHis', time()),
  ),
  'body' => array(
    'orderCode' => $order_no,//商户订单号
    'totalAmount' => $str_money,//订单金额 12 位，例 000000000101 代表1.01 元
    'subject' => '话费充值',//订单标题 
    'body' => 'iPhone',//订单描述
    'payMode' => '',//支付模式
    'payExtra' => json_encode(array('payType' =>'1', 'bankCode' => $_REQUEST['bank_code'])),
    'clientIp' => getClientIp(),//客户端 IP
    'notifyUrl' => $merchant_url,//异步通知地址
    'frontUrl' => $return_url,//前台通知地址  
    )
  );
  #变更参数设置

$form_url = 'https://cashier.sandpay.com.cn/gateway/api/order/pay';//提交地址
$scan = 'wy';
$data['head']['productId'] = '00000007';//网银B2C 00000007
$data['body']['payMode'] = 'bank_pc';//银行网关支付
$payType = $pay_type."_wy";
$bankname = $pay_type . "->网银在线充值";
// if (_is_mobile()) {
//   $form_url ='https://cashier.sandpay.com.cn/gateway/api/order/pay';//h5提供交地址
//   $data['head']['method'] = 'sandpay.trade.pay';//统一下单 sandpay.trade.pay
//   $data['head']['productId'] = '00000006';//支付宝服务窗支付 00000006
//   unset($data['body']['payTool']);
//   unset($data['body']['limitPay']);
//   $data['body']['payMode'] = '00000006';//支付模式
// }
// if (_is_mobile()) {
//   $form_url = 'http://a.bzzdp.com/api/createWapOrder';//wap提交地址
// }else {
//   $form_url = 'http://a.bzzdp.com/api/createOrder';//扫码提交地址
// }
// if (strstr($_REQUEST['pay_type'], "京东钱包")) {
//   $scan = 'jd';
//   $data['payWay'] = 'jd';
//   $bankname = $pay_type."->京东钱包在线充值";
//   $payType = $pay_type."_jd";
// }elseif (strstr($_REQUEST['pay_type'], "QQ钱包") || strstr($_REQUEST['pay_type'], "qq钱包")) {
//   $scan = 'qq';
//   $data['payWay'] = 'qq';
//   $bankname = $pay_type."->QQ钱包在线充值";
//   $payType = $pay_type."_qq";
// }elseif (strstr($_REQUEST['pay_type'], "百度钱包")) {
//   $scan = 'bd';
//   $data['payWay'] = 'baidu';
//   $bankname = $pay_type."->百度钱包在线充值";
//   $payType = $pay_type."_bd";
// }
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
$sign = sign($data,$pay_mkey);

#curl获取响应值
//拼接post数据
$post = array(
  'charset' => 'utf-8',
  'signType' => '01',
  'data' => json_encode($data),
  'sign' => $sign
);

$res = curl_post($form_url,http_build_query($post));
$row = parse_result($res);
#跳转

echo '<pre>';
echo ('<br> 请求报文 : <br>');
var_dump($data);
echo ('<br> 签名字串 : <br>');
echo ($sign);
echo ('<br><br> 响应值 : <br>');
var_dump($res);
echo ('<br><br> 响应值阵列 : <br>');
var_dump($row);
echo '</pre>';

if ($row['head']['respCode'] != '000000') {
  echo  '错误代码:' . $row['head']['respCode']."\n";
  echo  '错误讯息:' . $row['head']['respMsg']."\n";
  echo '<pre>';
  echo ('<br> 请求报文 : <br>');
  var_dump($data);
  echo ('<br> 签名字串 : <br>');
  echo ($signtext);
  echo ('<br><br> 响应值 : <br>');
  var_dump($res);
  echo ('<br><br> 响应值阵列 : <br>');
  var_dump($row);
  echo '</pre>';
  exit;
}else {
    $jumpurl = '../qrcode/qrcode.php?type='.$scan.'&code=' .QRcodeUrl($array['body']['realSumbitUrl']);
}
#跳轉方法

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
