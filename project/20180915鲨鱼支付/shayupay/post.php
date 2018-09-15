<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

#function
function payType_bankname($scan, $pay_type)
{
  global $payType, $bankname;
  if ($scan == "wy") {
    $payType = $pay_type . "_wy";
    $bankname = $pay_type . "->网银在线充值";
  } elseif ($scan == "yl" || $scan == "ylfs") {
    $payType = $pay_type . "_yl";
    $bankname = $pay_type . "->银联钱包在线充值";
  } elseif ($scan == "qq" || $scan == "qqfs") {
    $payType = $pay_type . "_qq";
    $bankname = $pay_type . "->QQ钱包在线充值";
  } elseif ($scan == "wx" || $scan == "wxfs") {
    $payType = $pay_type . "_wx";
    $bankname = $pay_type . "->微信在线充值";
  } elseif ($scan == "zfb" || $scan == "zfbfs") {
    $payType = $pay_type . "_zfb";
    $bankname = $pay_type . "->支付宝在线充值";
  } elseif ($scan == "jd" || $scan == "jdfs") {
    $payType = $pay_type . "_jd";
    $bankname = $pay_type . "->京东钱包在线充值";
  } elseif ($scan == "ylkj") {
    $payType = $pay_type . "_ylkj";
    $bankname = $pay_type . "->银联快捷在线充值";
  } elseif ($scan == "bd" || $scan == "bdfs") {
    $payType = $pay_type . "_bd";
    $bankname = $pay_type . "->百度钱包在线充值";
  } else {
    echo ('payType_bankname出错啦！');
    exit;
  }
}

function QRcodeUrl($code)
{ #替换QRcodeUrl中&符号
  if (strstr($code, "&")) {
    $code2 = str_replace("&", "aabbcc", $code);//有&换成aabbcc
  } else {
    $code2 = $code;
  }
  return $code2;
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

#第三方参数设置
$data = array(
  "p0_Cmd" => 'Buy', //业务类型
  "p1_MerId" => $pay_mid, //商户编号
  "p2_Order" => $order_no, //商户订单号
  "p3_Amt" => number_format($_REQUEST['MOAmount'], 2, '.', ''), //支付金额
  "p4_Cur" => 'CNY', //交易币种
  "p5_Pid" => 'Pid', //商品名称
  "p6_Pcat" => 'Pcat', //商品种类
  "p7_Pdesc" => 'Pdesc', //商品描述
  "p8_Url" => $merchant_url, //商户接收支付成功数据的地址
  "p9_SAF" => '0', //送货地址
  "pa_MP" => 'MP', //商户扩展信息
  "pd_FrpId" => '', //支付通道编码
  "pr_NeedResponse" => '1', //应答机制
  "hmac" => '', //签名数据
);

#变更参数设置
$form_url = 'http://shayufu.com/GateWay/ReceiveBank.aspx';//请求地址
if (strstr($pay_type, "银联钱包")) {
  $scan = 'yl';
  $data['pd_FrpId'] = 'unionpay';
} elseif (strstr($pay_type, "银联快捷")) {
  $scan = 'ylkj';
  $data['pd_FrpId'] = 'OnLineKJ';
} else {
  $scan = 'wy';
  $data['pd_FrpId'] = $_REQUEST['bank_code'];
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
$noarr = array('hmac');//不加入签名的array key值
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr)) {
    $signtext .= $arr_val;
  }
}
$key = $pay_mkey;
$data_signtext = $signtext;
$key = iconv("GB2312", "UTF-8", $key);
$data_signtext = iconv("GB2312", "UTF-8", $data_signtext);
$b = 64; // byte length for md5
if (strlen($key) > $b) {
  $key = pack("H*", md5($key));
}
$key = str_pad($key, $b, chr(0x00));
$ipad = str_pad('', $b, chr(0x36));
$opad = str_pad('', $b, chr(0x5c));
$k_ipad = $key ^ $ipad;
$k_opad = $key ^ $opad;
$sign = md5($k_opad . pack("H*", md5($k_ipad . $data_signtext)));
$data['hmac'] = $sign;

#跳轉方法
?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
  <form method="post" id="frm1" action="<?php echo $form_url ?>" target="_self">
     <p>正在为您跳转中，请稍候......</p>
       <?php foreach ($data as $arr_key => $arr_value) { ?>
         <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
       <?php 
    } ?>
   </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>

