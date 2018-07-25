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
	"order_type" => $order_type,//订单类型
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
  		$mymoney=number_format($total_fee/100, 2, '.', '');
		$result_insert = update_online_money($out_trade_no, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");	
		}else if($result_insert == 0){
			echo ("SUCCESS");
		}else if($result_insert == -2){
			echo ("数据库操作失败");
		}else if($result_insert == 1){
			echo ("SUCCESS");
		} else {
			echo ("支付失败");
		}
	}else{
		echo '签名不正确！';
		exit;
	}
}else{
	echo '交易失败！';
	exit;
}

?>
