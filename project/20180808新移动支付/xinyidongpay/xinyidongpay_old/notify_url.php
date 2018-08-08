<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");//原数据库的连接方式
// include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");

if ($_GET['out_trade_no'] && $_GET['total_amount']) {
	// write_log("return");
	// write_log($_GET['out_trade_no']);
	// write_log($_GET['total_amount']);
	$order_no = $_GET['out_trade_no']; //订单号
	$mymoney = number_format($_GET['total_amount'], 2, '.', ''); //订单金额
	$message = ("支付成功"); ?>
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
					<label id="lborderno"><?php echo $order_no; ?></label>
				</td>
			</tr>
			<tr>
				<td style="width: 120px; text-align: right;">充值金额：</td>
				<td style="padding-left: 10px;">
					<label id="lbpayamount"><?php echo $mymoney; ?></label>
				</td>
			</tr>
			
			<tr>
				<td style="width: 120px; text-align: right;">处理结果：</td>
				<td style="padding-left: 10px;">
					<label id="lbmessage"><?php echo $message; ?></label>
				</td>
			</tr>
			<tr>
				<td style="width: 120px; text-align: right;">备注</td>
				<td style="padding-left: 10px;">
					<label id="lbmessage">该页面仅作为通知用，若与支付平台不相符时，则以支付平台结果为准</label>
				</td>
			</tr>
			
		</table>
	</body>
	</html>

<?php
}else {
	// write_log("notify");
	$data = array();
	#input方法
	$input_data=file_get_contents("php://input");
	// write_log($input_data);
	$str = explode("|",$input_data);
	$data = json_decode($str[1],1);
	// write_log("json=".$str[1]);
	#设定固定参数
	$order_no = $data['data']['orderId']; //订单号
	$mymoney = number_format($data['data']['orderAmount'], 2, '.', ''); //订单金额
	$success_msg = $data['code'];//成功讯息
	$success_code = "000000";//文档上的成功讯息
	$sign = $str[0];//签名
	$echo_msg = "success";//回调讯息

	#根据订单号读取资料库
	$params = array(':m_order' => $order_no);
	$sql = "select operator from k_money where m_order=:m_order";
	$stmt = $mydata1_db->prepare($sql);//原数据库的连接方式
	// $stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
	$stmt->execute($params);
	$row = $stmt->fetch();

	#获取该订单的支付名称
	$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
	$params = array(':pay_type' => $pay_type);
	$sql = "select * from pay_set where pay_type=:pay_type";
	$stmt = $mydata1_db->prepare($sql);//原数据库的连接方式
	// $stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
	$stmt->execute($params);
	$payInfo = $stmt->fetch();
	$pay_mid = $payInfo['mer_id'];
	$pay_mkey = $payInfo['mer_key'];
	$pay_account = $payInfo['mer_account'];
	if ($pay_mid == "" || $pay_mkey == "") {
		echo "非法提交参数";
		// write_log("非法提交参数");
		exit;
	}
	$base64json = base64_encode($str[1]);
	$md5josn = md5($base64json);
	// write_log("md5josn=".$md5josn);
	$signtext = $pay_mkey.$md5josn;
	$mysign = strtoupper(md5($signtext));
	// write_log("signtext=".$signtext);
	// write_log("mysign=".$mysign);

	#到账判断
	if ($success_msg == $success_code) {
	if ( $mysign == $sign) {
			$result_insert = update_online_money($order_no, $mymoney);
			if ($result_insert == -1) {
				echo ("会员信息不存在，无法入账");
				// write_log("会员信息不存在，无法入账");
				exit;
			}else if($result_insert == 0){
				echo ($echo_msg);
				// write_log($echo_msg.'at 0');
				exit;
			}else if($result_insert == -2){
				echo ("数据库操作失败");
				// write_log("数据库操作失败");
				exit;
			}else if($result_insert == 1){
				echo ($echo_msg);
				// write_log($echo_msg.'at 1');
				exit;
			} else {
				echo ("支付失败");
				// write_log("支付失败");
				exit;
			}
		}else{
			echo ('签名不正确！');
			// write_log("签名不正确！");
			exit;
		}
	}else{
		echo ("交易失败");
		// write_log("交易失败");
		exit;
	}
}
?>
