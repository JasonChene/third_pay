<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

#function
function curl_post($url, $data)
{ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
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

#第三方参数设置
$data = array(
  //基本参数
  "merchantId" => $pay_mid, //商户号
  "notifyUrl" => $merchant_url, //通知URL
  "sign" => '', //签名
  //业务参数
  "outOrderId" => $order_no, //交易号
  "subject" => 'teddy', //订单名称
  "body" => 'itssofluffy', //订单描述
  "transAmt" => number_format($_REQUEST['MOAmount'], 0, '.', ''), //交易金额
  "scanType" => '20000002', //扫码类型
);

#变更参数设置
$scan = 'zfb';
$data['scanType'] = '10000001';
$form_url = 'https://payment.51bftpay.com/sfpay/scanCodePayServlet';//扫码提交地址
if (_is_mobile()) {
  $data['scanType'] = '10000002';
  $form_url = 'https://payment.51bftpay.com/sfpay/h5PayServlet';//wap提交地址
}
$bankname = $pay_type . "->支付宝在线充值";
$payType = $pay_type . "_zfb";

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
ksort($data);
$noarr = array('sign', 'signType');//不加入签名的array key值
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signtext .= $arr_key . '=' . $arr_val . '&';
  }
}
$signtext = substr($signtext, 0, -1);

function rsaSendSign($data, $merid, $key)
{
  $key = openssl_get_privatekey($key);
  if (!$key) {
    echo '打开私钥失败';
    exit;
  }
  openssl_sign($data, $sign, $key);
  openssl_free_key($key);//释放密钥资源
  $sign = base64_encode($sign);
  return $sign;
}

$data['sign'] = rsaSendSign($signtext, $merid, $pay_mkey);

if (!_is_mobile()) {
#curl获取响应值
  $res = curl_post($form_url, http_build_query($data));
  $tran = mb_convert_encoding($res, "UTF-8");
  $row = json_decode($tran, 1);

//打印
  echo '<pre>';
  echo ('<br> data = <br>');
  var_dump($data);
  echo ('<br> signtext = <br>');
  echo ($signtext);
  echo ('<br><br> row = <br>');
  var_dump($row);
  echo '</pre>';
  // exit;

#跳转
  if ($row['respCode'] != '00' && $row['respCode'] != '99') {
    echo '应答码:' . $row['respCode'] . "<br>";
    echo '应答描述:' . $row['respMsg'] . "<br>";
    exit;
  } else {
    if (!_is_mobile()) {
      if (strstr($row['payCode'], "&")) {
        $code = str_replace("&", "aabbcc", $row['payCode']);//有&换成aabbcc
      } else {
        $code = $row['payCode'];
      }
      $jumpurl = ('../qrcode/qrcode.php?type=' . $scan . '&code=' . $code);
    } else {
      $jumpurl = $row['payCode'];
    }
  }
} else {
  $jumpurl = $form_url;
}
#跳轉方法
?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form method="post" id="frm1" action="<?php echo $jumpurl ?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
      <?php if (_is_mobile()) { ?>
        <?php foreach ($data as $arr_key => $arr_value) { ?>      
          <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
        <?php 
      } ?>
      <?php 
    } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>

