<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");
include_once("function.php");


$trade_no = trim($_REQUEST['trade_no']);
$trade_type = trim($_REQUEST['trade_type']);
$time_start = trim($_REQUEST['time_start']);
$pay_time = trim($_REQUEST['pay_time']);
$goods_name = trim($_REQUEST['goods_name']);
$goods_detail = trim($_REQUEST['goods_detail']);
$fee_type = trim($_REQUEST['fee_type']);
$orig_trade_no = trim($_REQUEST['orig_trade_no']);
$mchid = trim($_REQUEST['mchid']);
$src_code = trim($_REQUEST['src_code']);
$total_fee = trim($_REQUEST['total_fee']);
$out_mchid = trim($_REQUEST['out_mchid']);
$cancel = trim($_REQUEST['cancel']);
$order_status = trim($_REQUEST['order_status']);
$sign = trim($_REQUEST['sign']);//签名
$time_expire = trim($_REQUEST['time_expire']);
$out_trade_no = trim($_REQUEST['out_trade_no']);
$order_type = trim($_REQUEST['order_type']);

$params = array(':m_order' => $out_trade_no);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));

$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];

if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	
	exit;
}
$parms=array(
	"trade_no" => $trade_no,//平台订单号
	"trade_type" => $trade_type,//交易类型
	"time_start" => $time_start,//发起交易的时间
	"pay_time" => $pay_time,//交易时间
	"goods_name" => $goods_name,//商品名称
	"fee_type" => $fee_type,//货币类型（默认CNY）
	"src_code" => $src_code,//商户唯一标识
	"total_fee" => $total_fee,//订单总金额，单位分
	"cancel" => $cancel,//是否已退款,无退款:1;已退款:2
	"order_status" => $order_status,//订单状态
	"time_expire" => $time_expire,//订单有效期
	"out_trade_no" => $out_trade_no,//接入的交易订单号
	"order_type" => $order_type//订单类型
);

if($orig_trade_no !=""){
	$parms['orig_trade_no']=$orig_trade_no;//外部订单号
}	
if($goods_detail !=""){
	$parms['goods_detail']=$goods_detail;//商品详情
}	
if ($out_mchid !="" && $mchid =="") {
	$parms['out_mchid']=$out_mchid;//接入方商户号
}else{
	$parms['mchid']=$mchid;//商户号
}
ksort($parms);
$mysign=get_md5($parms,$pay_mkey);




if ($order_status == "3") {
	//1:下单中；2:等待支付；3:支付成功；4:支付失败；6:用户未支付
  if ($sign == $mysign) {
		if ($result_insert == -1) {
			$message="会员信息不存在，无法入账";		
		} else if ($result_insert == 0) {
			$message="SUCCESS";
		} else if ($result_insert == -2) {
			$message="数据库操作失败";
		} else if ($result_insert == 1) {
			$message="SUCCESS";
		} else {
			$message="支付失败";
		}
	} else {
		$message="签名不正确！";
		exit;
	}
} else {
	$message="交易失败！";
	exit;
}

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>支付结果展示</title>
    <style type="text/css">
        *,html,body{ background: #fff;font-size: 14px;font-family: "Microsoft Yahei", "微软雅黑"}
        html,body{ width: 100%;margin: 0;padding: 0;}
        table .tips{ background: #F0F0FF;height: 35px;line-height: 35px;padding-left: 5px;font-weight: 600;}
    </style>
</head>
<body>
	<table width="98%" border="1" cellspacing="0" cellpadding="3" bordercolordark="#fff" bordercolorlight="#d3d3d3" style="margin: 10px auto;">
		<tr>
			<td colspan="2" class="tips">处理结果</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">商户订单号：</td>
			<td style="padding-left: 10px;">
				<label id="lborderid"><?php echo $out_trade_no; ?></label>
			</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">充值金额：</td>
			<td style="padding-left: 10px;">
				<label id="lbpayamount"><?php echo $total_fee/100; ?></label>
			</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">处理结果：</td>
			<td style="padding-left: 10px;">
				<label id="lbmessage"><?php echo $message; ?></label>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="button" value="关闭"/>
			</td>
		</tr>
	</table>
</body>
</html>