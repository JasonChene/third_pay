<?php
header("Content-type:text/html; charset=utf-8");
// include_once("../../../database/mysql.config.php");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");

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
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $tmpInfo = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
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

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商戶密钥
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
$form_url ='http://pay.taikangxm.cn:31588/payment/PayApply.do';
#第三方参数设置
$data =array(
    'versionId' => '1.0',//服务版本号
    'orderAmount' => number_format($_REQUEST['MOAmount']*100,0, '.', ''),//訂單金额 以分为单位
    'orderDate' => date("YmdHis"),//订单日期
    'currency' => 'RMB',//货币类型
    'transType' => '0008',//交易类别
    'asynNotifyUrl' => $merchant_url,//异步通知地址
    'synNotifyUrl' => $return_url,//同步通知地址
    'signType' => 'MD5',//加密方式
    'merId' => $pay_mid,//商户编号
    'prdOrdNo' => $order_no,//商户订单号
    'payMode' => "",//支付方式
    'tranChannel' => '',//银行编码
    'receivableType' => 'D00',//到账类型
    'prdName' => 'iphone',//商品名称
    'signData' => ''//加密数据
);
#变更参数设置
$scan = 'wy';
$payType = $pay_type."_wy";
$bankname = $pay_type . "->网银在线充值";
$data['payMode'] = '00020';//支付方式，00019-银行卡快捷(固定值) 00020-网银(固定值)
$data['tranChannel'] = $_REQUEST['bank_code'];//银行编码
if (strstr($_REQUEST['pay_type'], "银联快捷")) {
    if (isset($_REQUEST['cardNo']) && isset($_REQUEST['cerdId']) && isset($_REQUEST['acctName'])) {
        $scan = 'ylkj';
        unset($data['tranChannel']);
        $data['cardNo'] = $_REQUEST['cardNo'];//快捷支付银行
        $data['cerdId'] = $_REQUEST['cerdId'];//快捷支付身份证
        $data['acctName'] = $_REQUEST['acctName'];//持卡人姓名
        $data['payMode'] = '00019';//支付方式，00019-银行卡快捷(固定值) 00020-网银(固定值)
        $bankname = $pay_type."->银联快捷在线充值";
        $payType = $pay_type."_ylkj";
    }else {
        ?>
        <html>
          <head>
            <title>跳转......</title>
            <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
          </head>
          <body>
            <form name="dinpayForm" method="get" id="frm1" action="./card.php" target="_self">
              <p>正在为您跳转中，请稍候......</p>
              <?php foreach ($_REQUEST as $arr_key => $arr_value) {?>
              <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
              <?php } ?>
            </form>
            <script language="javascript">
              document.getElementById("frm1").submit();
            </script>
          </body>
        </html>
        <?php
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
ksort($data);
$signtext = '';
$noarr = array('signData');
foreach ($data as $arr_key => $arr_val) {
    if ( !in_array($arr_key, $noarr) && !empty($arr_val) )  {
        $signtext .= $arr_key.'='.$arr_val.'&';
	}
}
$signtext = substr($signtext, 0 , -1) .'&key='.  $pay_mkey;
$sign = strtoupper(md5(mb_convert_encoding($signtext, "UTF-8", "GB2312")));
$data['signData'] = $sign;

#curl获取响应值
$res = curl_post($form_url,http_build_query($data));
echo($res);

#跳轉方法
?>
<!-- <html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="get" id="frm1" action="<?php echo $form_url; ?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
      <?php foreach ($data as $arr_key => $arr_value) {?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html> -->
