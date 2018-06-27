<? header("content-Type: text/html; charset=UTF-8");?>
<?php
	
	/////////////////////////////////获取智付公钥（在商家后台复制出来）//////////////////////
	////////////////////////get the publickey of dinpay/////////////////////////////////////
//include_once("../config.php");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
	
	//////////////////////////		接收智付返回通知数据  /////////////////////////////////
	////////////////////////// To receive notification data from Dinpay ////////////////////
	
$orderno = $_REQUEST["outorderno"]; 

$params = array(':m_order'=>$orderno);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$payType = substr($row['operator'] , 0 , strripos($row['operator'],"_"));

$params = array(':pay_type'=>$payType);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
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
$data = array();
$data['status'] = $_REQUEST["status"]; 
$data['extraparam'] = $_REQUEST["extraparam"];
$data['charset'] = $_REQUEST["charset"];
$data['transactionid'] = $_REQUEST["transactionid"];
$data['outtransactionid'] = $_REQUEST["outtransactionid"];
$data['outorderno'] = $_REQUEST["outorderno"];
$data['totalfee'] = $_REQUEST["totalfee"];
$data['mchid'] = $pay_mid;
$sign = $_REQUEST["sign"];

$temp='';
ksort($data);//对数组进行排序
//遍历数组进行字符串的拼接
foreach ($data as $x=>$x_value){
    if ($x_value != null&&$x_value != "null"&&$x_value != ""){
        $temp = $temp.$x."=".$x_value."&";
    }
}


	$signture=md5($temp."key=".$pay_mkey);

$ordernomsg = $data['outorderno'];
$amountmsg = $data['totalfee']/100;

		
	if($signture == $sign)
	{		//验签成功（Signature correct）
		if($data['status'] == '100')
		{
			$result_insert = update_online_money($ordernomsg,$amountmsg);
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
		else 
		{
			$message =  '交易失败！';
		}	

	}else{

		$message =  '签名不正确！';  //验签失败，业务结束（End of the business）
	}
?>

<!DOCTYPE html>

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
				<label id="lborderid"><?php echo $ordernomsg; ?></label>
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