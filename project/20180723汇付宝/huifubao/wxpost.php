<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

function payType_bankname($scan, $pay_type)
{
  global $payType, $bankname;
  if (strstr($scan, "wy")) {
    $payType = $pay_type . "_wy";
    $bankname = $pay_type . "->网银在线充值";
  } elseif (strstr($scan, "yl")) {
    $payType = $pay_type . "_yl";
    $bankname = $pay_type . "->银联钱包在线充值";
  } elseif (strstr($scan, "qq")) {
    $payType = $pay_type . "_qq";
    $bankname = $pay_type . "->QQ钱包在线充值";
  } elseif (strstr($scan, "wx")) {
    $payType = $pay_type . "_wx";
    $bankname = $pay_type . "->微信在线充值";
  } elseif (strstr($scan, "zfb")) {
    $payType = $pay_type . "_zfb";
    $bankname = $pay_type . "->支付宝在线充值";
  } elseif (strstr($scan, "jd")) {
    $payType = $pay_type . "_jd";
    $bankname = $pay_type . "->京东钱包在线充值";
  } elseif (strstr($scan, "ylkj")) {
    $payType = $pay_type . "_ylkj";
    $bankname = $pay_type . "->银联快捷在线充值";
  } elseif (strstr($scan, "bd")) {
    $payType = $pay_type . "_bd";
    $bankname = $pay_type . "->百度钱包在线充值";
  }
}

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商戶私钥
$pay_account = $row['mer_account'];
$return_url = $row['mer_account'] . $row['wx_returnUrl'];//return跳转地址
$merchant_url = $row['mer_account'] . $row['wx_synUrl'];//notify回传地址
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}
#固定参数设置
$top_uid = $_REQUEST['top_uid'];
$order_no = getOrderNo();
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');

#第三方参数设置
$data = array(
  "version" => '1',
  "pay_type" => "30",
  "agent_id" => $pay_mid, //商户号
  "agent_bill_id" => $order_no,//商户流水号
  "pay_amt" => number_format($_REQUEST['MOAmount'], 2, '.', ''),//订单金额：单位/元
  "notify_url" => $merchant_url,//异步通知地址
  "return_url" => $return_url,//同步通知地址
  "user_ip" => getClientIp(),
  "agent_bill_time" => date("YmdHis"),
  "goods_name" => 'Buy',//商品名称
  "remark" => 'yesOhyes'//备注
);
#变更参数设置
$form_url = 'https://pay.heepay.com/Payment/Index.aspx';//提交地址

if (strstr($pay_type, "京东钱包")) {
  $scan = 'jd';
  $data['pay_type'] = '33';
  if (_is_mobile()) {
    $data['is_phone'] = '1';
  }
} else {
  $scan = 'wx';
  if (_is_mobile()) {
    $data['is_phone'] = '1';
    $data['is_frame'] = '0';
    $data['meta_option'] = urlencode(base64_encode('{"s":"WAP","n":"Pay","id":' . $row['pay_domain'] . '}'));
  }
}
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

$signtext = '';
$conn = '&';
$betcon = '=';
$signtext .= 'version' . $betcon . $data['version'] . $conn;
$signtext .= 'agent_id' . $betcon . $data['agent_id'] . $conn;
$signtext .= 'agent_bill_id' . $betcon . $data['agent_bill_id'] . $conn;
$signtext .= 'agent_bill_time' . $betcon . $data['agent_bill_time'] . $conn;
$signtext .= 'pay_type' . $betcon . $data['pay_type'] . $conn;
$signtext .= 'pay_amt' . $betcon . $data['pay_amt'] . $conn;
$signtext .= 'notify_url' . $betcon . $data['notify_url'] . $conn;
$signtext .= 'return_url' . $betcon . $data['return_url'] . $conn;
$signtext .= 'user_ip' . $betcon . $data['user_ip'] . $conn;
$signtext .= 'key' . $betcon . $pay_mkey;
$sign = md5($signtext);
$data['sign'] = $sign;
#跳轉方法

?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $form_url ?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
      <?php
      foreach ($data as $arr_key => $arr_value) {
        ?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php 
    } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>

