<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
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

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
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
    'receivableType' => 'D00',//到账类型
    'prdAmt' => number_format($_REQUEST['MOAmount']*100,0, '.', ''),//商品价格 以分为单位 扫码必填
    'prdName' => 'iphone',//商品名称
    'signData' => ''//加密数据
);
#变更参数设置
$form_url ='http://106.14.211.216:8070/payment/ScanPayApply.do';//扫码网关
$scan = 'zfb';
$payType = $pay_type."_zfb";
$bankname = $pay_type . "->支付宝在线充值";
$data['payMode'] = '00021';//00021-支付宝扫码 00022-微信扫码00024-QQ扫码
if (_is_mobile()) {
    $form_url ='http://106.14.211.216:8070/payment/PayApply.do';//h5网关
    unset($data['prdAmt']);
    $data['payMode'] = '00028';//00028-支付宝H5 00016-微信H5 文档上没有的新通道支付宝h5 10029
    $data['pnum'] = '1';//商品数量
    $data['prdDesc'] = 'iphone';//商品描述
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
$signtext = substr($signtext, 0 , -1) .'&key='.  $pay_mkey;//demo档有加上key= 文档没有
$sign = strtoupper(md5(mb_convert_encoding($signtext, "UTF-8", "GB2312")));
$data['signData'] = $sign;

if (!_is_mobile()) {
  #curl获取响应值
  $res = curl_post($form_url,$data);
  $tran = mb_convert_encoding($res, "UTF-8");
  $row = json_decode($tran, 1);
  
  #跳轉方法
  if ($row['retCode'] != '1') {
    echo '返回状态码:' . $row['status'] . "\n";//返回状态码
    echo '返回信息:' . $row['retMsg'] . "\n";//返回信息
    echo '<pre>';
    echo '请求报文：<br>';
    var_dump($data);
    echo '响应报文：<br>';
    var_dump($res);
    echo '响应报文阵列：<br>';
    var_dump($row);
    exit;
  } else {
    #不是手机
    $jumpurl = '../qrcode/qrcode.php?type=' . $scan . '&code=' . QRcodeUrl($row['qrcode']);
  }
}else {
  #是手机的话
  $jumpurl = $form_url;
  $form_data =$data;
}

?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl; ?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>

      <?php if (isset($form_data)) { foreach ($form_data as $arr_key => $arr_value) {?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php }} ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
