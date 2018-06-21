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
  "shid" => $pay_mid, //商户编号
  "bb" => '1.0', //版本号
  "zftd" => '', //支付通道
  "ddh" => $order_no, //商户订单号
  "je" => number_format($_REQUEST['MOAmount'], 2, '.', ''), //订单金额
  "ddmc" => 'unicorn', //订单名称
  "ddbz" => 'itssofluffy', //订单备注
  "ybtz" => $merchant_url, //异步通知URL
  "tbtz" => $return_url, //同步跳转URL
  "sign" => ''//md5签名串
);

#变更参数设置
$form_url = 'http://yspay.co/pay/api.php';//扫码提交地址
if (strstr($pay_type, "银联钱包")) {
  $scan = 'yl';
  $data['zftd'] = 'yishi';
  $bankname = $pay_type . "->银联钱包在线充值";
  $payType = $pay_type . "_yl";
} elseif (strstr($pay_type, "银联快捷")) {
  $scan = 'ylkj';
  $data['zftd'] = 'shkj';
  $bankname = $pay_type . "->银联快捷在线充值";
  $payType = $pay_type . "_ylkj";
  $data['bankId'] = $_REQUEST['bank_code'];
} else {
  $scan = 'wy';
  $data['zftd'] = 'shwg';
  $bankname = $pay_type . "->网银在线充值";
  $payType = $pay_type . "_wy";
  $data['bankId'] = $_REQUEST['bank_code'];
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
$noarr = array('sign');//不加入签名的array key值
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signtext .= $arr_key . '=' . $arr_val . '&';
  }
}
$signtext = substr($signtext, 0, -1) . '&' . $pay_mkey;
$sign = md5($signtext);
$data['sign'] = $sign;
$data_str = http_build_query($data);

#curl获取响应值
$res = curl_post($form_url, $data_str);
//正则表达式
$pattern = '/{.*?}/';
preg_match_all($pattern, $res, $matches);
$mat = $matches[0][0];

$tran = mb_convert_encoding($mat, "UTF-8");
$ipslashes = stripslashes($tran);//去除反斜线
$row = json_decode($ipslashes, 1);

#跳转
if ($scan == 'yl') {
  if ($row['status'] != 'success') {

    echo '<pre>';
    echo ('<br> 请求报文 = <br>');
    var_dump($data);
    echo ('<br> 签名字串 = <br>');
    echo ($signtext);
    echo ('<br><br> 响应值阵列 = <br>');
    var_dump($row);
    echo '</pre>';

    echo '返回状态码 : ' . $row['status'] . "\n";//返回状态码
    echo '返回信息 : ' . $row['msg'] . "\n";//返回信息
    exit;
  } else {
    if (!_is_mobile()) {
      if (strstr($row['qrCodeURL'], "&")) {
        $code = str_replace("&", "aabbcc", $row['qrCodeURL']);//有&换成aabbcc
      } else {
        $code = $row['qrCodeURL'];
      }
      $jumpurl = ('../qrcode/qrcode.php?type=' . $scan . '&code=' . $code);
    } else {
      $jumpurl = urldecode($row['qrCodeURL']);
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
      <?php if ($scan != 'yl') { ?>
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

