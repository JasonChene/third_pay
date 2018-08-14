<? header("content-Type: text/html; charset=UTF-8");?>
<?php


//include_once("../config.php");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

$orderno=$_POST["outOrderId"];//商户订单号

$params = array(':m_order'=>$orderno);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$payType = substr($row['operator'] , 0 , strripos($row['operator'],"_"));

$params = array(':pay_type'=>$payType);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];

if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}

$merchantCode=$_POST["merchantCode"];
$instructCode=$_POST["instructCode"];
$transType=$_POST["transType"];
$outOrderId=$_POST["outOrderId"];//商户订单号
$transTime=$_POST["transTime"];
$totalAmount=$_POST["totalAmount"];//充值金额（分）
$ext =$_POST["ext"];
$sign=$_POST["sign"];//md5签名

$temp = "";
$temp = $temp."instructCode=".$instructCode."&";
$temp = $temp."merchantCode=".$merchantCode."&";
$temp = $temp."outOrderId=".$outOrderId."&";
$temp = $temp."totalAmount=".$totalAmount."&";
$temp = $temp."transTime=".$transTime."&";
$temp = $temp."transType=".$transType."&";
$temp = $temp."KEY=".$pay_mkey;

$signture =strtoupper(MD5($temp));

// 支付结果信息前台显示，统一变量下面显示的html代码不用再去修改
$ordernomsg = $outOrderId;
$amountmsg = ($totalAmount/100);

if($sign == $signture){
		$result_insert = update_online_money($outOrderId,($totalAmount/100));
    	if ($result_insert==-1) {
			$message = "会员信息不存在，无法入账";
		} else if ($result_insert==0) {
			$message = "会员已经入账，无需重复入账";
		} else if ($result_insert==-2) {
			$message = "数据库操作失败";
		} else if ($result_insert==1) {
			$message = "支付成功！";
		} else {
			$message = "支付失败,请重新支付！";
		}
}	
else{
	$message = "支付失败,请重新支付！";
}

?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>支付同步结果展示</title>
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
			<td style="width: 120px; text-align: right;">订单号：</td>
			<td style="padding-left: 10px;">
				<label id="lborderno"><?php echo $ordernomsg; ?></label>
			</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">充值金额：</td>
			<td style="padding-left: 10px;">
				<label id="lbpayamount"><?php echo $amountmsg; ?></label>
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