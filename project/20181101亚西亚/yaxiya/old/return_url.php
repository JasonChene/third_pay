<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

// write_log("return");

#接收资料
#REQUEST方法
$data = array();
foreach ($_REQUEST as $key => $value) {
	$data[$key] = $value;
	// write_log($key."=".$value);
}
$message = "支付成功";
$manyshow = 0;
if(!empty($data)){
	$manyshow = 1;
	#设定固定参数
	$order_no = $data['orderid']; //订单号
}
?>

<!-- Html顯示充值資訊 須改變訂單echo變數名稱-->
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
		<?php 
			if($manyshow == 1){
		?>
		<tr>
			<td style="width: 120px; text-align: right;">订单号：</td>
			<td style="padding-left: 10px;">
				<label id="lborderno"><?php echo $order_no; ?></label>
			</td>
		</tr>
		<?php
			}
		?>
		<tr>
			<td style="width: 120px; text-align: right;">处理结果：</td>
			<td style="padding-left: 10px;">
				<label id="lbmessage"><?php echo $message; ?></label>
			</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">备注：</td>
			<td style="padding-left: 10px;">
				<label id="lbmessage">该页面仅作为通知用，若与支付平台不相符时，则以支付平台结果为准</label>
			</td>
		</tr>
		
	</table>
</body>
</html>
