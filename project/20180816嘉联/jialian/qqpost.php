<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");
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

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
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
  "pay_memberid" => $pay_mid, //商户号
  "pay_orderid" => $order_no,//商户流水号
  "pay_amount" => $mymoney,//订单金额：单位/元
  "pay_applydate" => date("Y-m-d H:i:s"),
  "pay_bankcode" => '908',//支付方式
  "pay_notifyurl" => $merchant_url,//通知地址
  "pay_callbackurl" => $return_url//商品名称
);
#变更参数设置
$form_url = 'http://www.51bugu.cc/Pay_Index';//提交地址
  $scan = 'qq';
  if(_is_mobile()){
    $data['pay_bankcode'] = '905';
  }
  $bankname = $pay_type."->QQ钱包在线充值";
  $payType = $pay_type."_qq";
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
$noarr =array('sign');
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if ( !in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val ===0 || $arr_val ==='0') ) {
		$signtext .= $arr_key.'='.$arr_val.'&';
	}
}
$signtext = substr($signtext,0,-1).'&key='.$pay_mkey;
$sign = strtoupper(md5($signtext));
$data['pay_md5sign'] = $sign;
#curl获取响应值
if (!_is_mobile()) {
$res = curl_post($form_url,$data);
$row = json_decode($res,1);

#跳转
if ($row['status'] != '0') {
  echo  '错误代码:' . $row['status']."<br>";
  echo  '错误讯息:' . $row['msg']."<br>";
  exit;
}else {
  
    if(strstr($row['codeUrl'],"&")){
      $code=str_replace("&", "aabbcc", $row['codeUrl']);//有&换成aabbcc
    }else{
      $code=$row['codeUrl'];
    }
    $jumpurl =('../qrcode/qrcode.php?type='.$scan.'&code=' .$code);
  }
}else{
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
    
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl?>" target="_self">
      <?php if(_is_mobile()){ ?>
        <?php foreach ($data as $arr_key => $arr_value) {?>      
          <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
        <?php } ?>
      <?php } ?>
      <p>正在为您跳转中，请稍候......</p>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>

