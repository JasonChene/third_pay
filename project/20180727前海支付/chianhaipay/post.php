<?php
header("Content-type:text/html; charset=utf-8");
// include_once("../../../database/mysql.config.php");//原数据库的连接方式
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");

#function
function curl_post($url, $data)
{ #POST访问
  $ch = curl_init();
  curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8', 'Content-Length:' . strlen($data)]);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
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

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);//原数据库的连接方式
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//appid
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
  "version" => "1.0", 
  "merId" => $pay_mid,
  "orderId" => $order_no,
  "totalMoney" => number_format($_REQUEST['MOAmount']*100, 0, '.', ''),//支付金金额
  "tradeType" => '',
  "ip" => getClientIp(),
  "describe" => "pay",
  "notify" => $merchant_url,
  "redirectUrl" => $return_url,
  "fromtype" => '',
  "sign" => ''
);

#变更参数设置
$form_url = 'http://pay.phcygmc.com:9091/business/order/prepareOrder';
$scan = '';
$payType = '';
$bankname = '';
$scan = 'wy';
$payType = $pay_type . "_wy";
$bankname = $pay_type . "->网银在线充值";
if (strstr($_REQUEST['pay_type'], "银联快捷")) {
  $form_url = "http://pay.phcygmc.com:9091/business/order/getPhoneCode";
  $data = array(
    "channelId" => "", //渠道号 平台分配
    "acctNo" => $pay_mid,//卡号
    "phoneNo" => $order_no,//持卡人手机
    "userName" => "",//持卡人姓名
    "cardType" => '',//证件类型
    "cardId" => '',//证件号
    "cvn2" => "",//安全码
    "expDate" => "",//卡到期时间
    "describe" => "pay",//商品名称
    "totalMoney" => number_format($_REQUEST['MOAmount']*100, 0, '.', ''),//支付金额
    "" => ''//????
  );
  $scan = 'ylkj';
  $bankname = $pay_type . "->银联快捷在线充值";
  $payType = $pay_type . "_ylkj";
}elseif (strstr($_REQUEST['pay_type'], "银联钱包")) {
  $scan = 'yl';
  $bankname = $pay_type . "->银联钱包在线充值";
  $payType = $pay_type . "_yl";
  $data['tradeType'] = 'unionpay';
  if (_is_mobile()) {
    $data['fromtype'] = 'wap';
  }
}

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
if ($scan == 'ylkj') {
  #curl获取响应值
  $res = curl_post($form_url, $data_json);
  $row = json_decode($res, 1);
  if ($row['result'] != '0') {
    echo '错误代码:' . $row['result'] . "<br>";
    echo '错误讯息:' . $row['errMsg'] . "<br>";
    exit;
  } else {
    echo '成功';
    exit;
  }
}else {
  $signtext = "merId=".$data['merId'];
  $signtext .= "&orderId=".$data['orderId'];
  $signtext .= "&totalMoney=".$data['totalMoney'];
  $signtext .= "&tradeType=".$data['tradeType'].$pay_mkey;
  $data['sign'] = strtoupper(md5($signtext));//簽名
  $data_json = json_encode($data,320);

  #curl获取响应值
  $res = curl_post($form_url, $data_json);
  $row = json_decode($res, 1);
  #跳转
  if ($row['code'] != '0') {
    echo '错误代码:' . $row['code'] . "<br>";
    exit;
  } else {
    if (_is_mobile()) {
      $jumpurl = $row['data'];
    } else {
      $jumpurl = '../qrcode/qrcode.php?type=' . $scan . '&code=' . QRcodeUrl($row['data']);
    }
  }
}
#跳轉方法

?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl ?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
      <?php
      if (isset($form_data)) {
        foreach ($form_data as $arr_key => $arr_value) {
          ?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php 
    }
  } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>


?>
