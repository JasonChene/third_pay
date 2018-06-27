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
    'totalAmount' =>  substr((string)$mymoney * 100 + pow(10, 12), 1),//订单金额 12 位，例 000000000101 代表1.01 元
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
if (strstr($_REQUEST['pay_type'], "银联快捷")) {
  $form_url = 'https://cashier.sandpay.com.cn/fastPay/quickPay/index';//提交地址
  $data['head']['method'] = 'sandPay.fastPay.quickPay.index';//银联快捷 一键快捷 接口名称
  $data['head']['productId'] = '00000016';//一键快捷 00000016
  $data['body']['userId'] = rand(0,99999999);//用户 ID（持卡用户在商户侧的唯一标识，最多 10 位数）
  $data['body']['orderTime'] = date('YmdHis', time());//商户上送的订单时间
  $data['body']['currencyCode'] = '156';//币种
  $data['body']['clearCycle'] = '0';//清算模式：  0：T1（默认）  1：T0  2：D0  
  $scan = 'ylkj';
  $bankname = $pay_type."->银联快捷在线充值";
  $payType = $pay_type."_ylkj";
}elseif (strstr($_REQUEST['pay_type'], "银联钱包")) {
  $form_url = 'https://cashier.sandpay.com.cn/qr/api/order/create';//提交地址
  $data['head']['method'] = 'sandpay.trade.precreate';//银联扫码 接口名称
  $data['head']['productId'] = '00000012';//银联正扫 00000012
  $data['body']['payTool'] = '0403';//0403：银联扫码
  // $data['body']['limitPay'] = '1';//限定支付方式
  $data['body']['currencyCode'] = '156';//币种
  $data['body']['clearCycle'] = '0';//清算模式：  0：T1（默认）  1：T0  2：D0
  unset($data['body']['payMode']);
  unset($data['body']['payExtra']);
  unset($data['body']['clientIp']);
  unset($data['body']['frontUrl']);
  $scan = 'yl';
  $bankname = $pay_type."->银联钱包在线充值";
  $payType = $pay_type."_yl";
}else {
  $form_url = 'https://cashier.sandpay.com.cn/gateway/api/order/pay';//提交地址
  $scan = 'wy';
  $data['head']['productId'] = '00000007';//网银B2C 00000007
  $data['body']['payMode'] = 'bank_pc';//银行网关支付
  $payType = $pay_type."_wy";
  $bankname = $pay_type . "->网银在线充值";
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
$sign = sign($data,$pay_mkey);

#curl获取响应值
//拼接post数据
$post = array(
  'charset' => 'utf-8',
  'signType' => '01',
  'data' => json_encode($data),
  'sign' => $sign
);
($scan == 'ylkj') ? $post['sign'] = urlencode($sign): $post['sign'] = $sign ;


//网银 银联钱包 银联快捷 判断
if ($scan == 'wy') {
  $res = curl_post($form_url,http_build_query($post));
  $row = parse_result($res);
  $res_data_arr = json_decode($row['data'],1);
  #跳转   
  if ($res_data_arr['head']['respCode'] != '000000') {
    echo  '错误代码:' . $res_data_arr['head']['respCode']."\n";
    echo  '错误讯息:' . $res_data_arr['head']['respMsg']."\n";
    exit;
  }elseif($res_data_arr['head']['respCode'] == '000000') {
    $credential = $res_data_arr['body']['credential'];
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
    <html>
      <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="renderer" content="webkit"/>
        <title>Insert title here</title>
        <script type="text/javascript" src="scripts/paymentjs.js"></script>
        <script type="text/javascript" src="scripts/jquery-1.7.2.min.js"></script>
      </head>
      <body>
      <script>
        function wap_pay() {
          var responseText = $("#credential").text();
          console.log(responseText);
          paymentjs.createPayment(responseText, function (result, err) {
              console.log(result);
              console.log(err.msg);
              console.log(err.extra);
          });
        }
      </script>
      <div style="display: none">
          <p id="credential"><?php echo $credential; ?></p>
      </div>
      </body>
      <script>
          window.onload = function () {
              wap_pay();
          };
      </script>
    </html>
    <?php 
  }
}elseif ($scan == 'yl') {
  $res = curl_post($form_url,http_build_query($post));
  $row = parse_result($res);
  $res_data_arr = json_decode($row['data'],1);
  if ($res_data_arr['head']['respCode'] != '000000') {
    echo  '错误代码:' . $res_data_arr['head']['respCode']."\n";
    echo  '错误讯息:' . $res_data_arr['head']['respMsg']."\n";
    exit;
  }else {
    $jumpurl = '../qrcode/qrcode.php?type='.$scan.'&code='.$res_data_arr['body']['qrCode'];
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
  <?php
} elseif ($scan == 'ylkj') {
  ?>
  <html>
    <head>
      <title>跳转......</title>
      <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
      <p>正在为您跳转中，请稍候......</p>
      <form name="dinpayForm" method="post" id="frm1" action="<?php echo $form_url?>" target="_self" hidden="hidden">
        <textarea name="charset">utf-8</textarea><br>
        <textarea name="signType">01</textarea><br>
        <textarea name="data" id="data"><?php echo $post['data'];?></textarea><br>
        <textarea name="sign" id="sign"><?php echo $post['sign'];?></textarea><br>
      </form>
      <script language="javascript">
        document.getElementById("frm1").submit();
      </script>
    </body>
  </html>
  <?php 
}

#跳轉方法

?>
