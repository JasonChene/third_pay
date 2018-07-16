<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");//原新数据库的连接方式
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);//原新数据库的连接方式
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
  "pay_memberid" => $pay_mid, //商户ID
  "pay_orderid" => $order_no, //订单号
  "pay_amount" => number_format($_REQUEST['MOAmount'], 2, '.', ''), //金额
  "pay_applydate" => date("Y-m-d H:i:s"), //订单提交时间
  "pay_bankcode" => 'WXZF', //银行编号
  "pay_notifyurl" => $merchant_url, //服务端返回地址
  "pay_callbackurl" => $return_url, // 页面返回地址
  "tongdao" => '', //调用通道编码
  "pay_reserved1" => '', //扩展字段1
  "pay_reserved2" => '', //扩展字段2
  "pay_reserved3" => '', //扩展字段3
  "pay_productname" => '', //商品名称
  "pay_productnum" => '', //商户品数量
  "pay_productdesc" => '', //商品描述
  "pay_producturl" => '', //商户链接地址
  "pay_md5sign" => '', //MD5签名字段
);

$form_url = 'http://yunhaopay.com/Pay_Index.html';//提交地址

$scan = 'wx';
$data['tongdao'] = 'MPWXSM';
$bankname = $pay_type . "->微信在线充值";
$payType = $pay_type . "_wx";

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
$noarr = array('tongdao', 'pay_reserved1', 'pay_reserved2', 'pay_reserved3', 'pay_productname', 'pay_productnum', 'pay_productdesc', 'pay_producturl', 'pay_md5sign');//不加入签名的array key值
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signtext .= $arr_key . '=>' . $arr_val . '&';
  }
}
$signtext = substr($signtext, 0, -1) . '&key=' . $pay_mkey;
$sign = strtoupper(md5($signtext));
$data['pay_md5sign'] = $sign;

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

