<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
// write_log("return");

$data = array();

#接收资料
#post方法
// write_log('REQUEST方法');
foreach ($_REQUEST as $key => $value) {
	$data[$key] = $value;
	// write_log($key . "=" . $value);
}
$manyshow = 0;
if(!empty($data)){
	$manyshow = 1;
	#设定固定参数
	$order_no = $data['orderNo']; //订单号


	#根据订单号读取资料库
	$params = array(':m_order' => $order_no);
	$sql = "select operator from k_money where m_order=:m_order";
	// $stmt = $mydata1_db->prepare($sql);
	$stmt = $mydata1_db->prepare($sql);
	$stmt->execute($params);
	$row = $stmt->fetch();

	#获取该订单的支付名称
	$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
	$params = array(':pay_type' => $pay_type);
	$sql = "select * from pay_set where pay_type=:pay_type";
	// $stmt = $mydata1_db->prepare($sql);
	$stmt = $mydata1_db->prepare($sql);
	$stmt->execute($params);
	$payInfo = $stmt->fetch();
	$pay_mid = $payInfo['mer_id'];
	$pay_mkey = $payInfo['mer_key'];
	$idArray = explode("###", $pay_mkey);
	$md5key = $idArray[0];//md5密钥
	$private_key = $idArray[1];//RSA私钥：
	$pay_account = $row['mer_account'];//RSA支付公钥
	if ($pay_mid == "" || $pay_mkey == "") {
		echo "非法提交参数";
		// write_log('非法提交参数');
		exit;
	}
	$public_pem = chunk_split($pay_account, 64, "\r\n");//转换为pem格式的公钥
	$public_pem = "-----BEGIN PUBLIC KEY-----\r\n" . $public_pem . "-----END PUBLIC KEY-----\r\n";
	$private_pem = chunk_split($private_key, 64, "\r\n");//转换为pem格式的私钥
	$private_pem = "-----BEGIN RSA PRIVATE KEY-----\r\n" . $private_pem . "-----END RSA PRIVATE KEY-----\r\n";

	$data = $_POST['data'];
	$pr_key = openssl_get_privatekey($private_pem);
	if ($pr_key == false){
		echo "打开密钥出错";
		die;
	}
	$data = base64_decode($data);
	$crypto = '';
	foreach (str_split($data, 128) as $chunk) {
		openssl_private_decrypt($chunk, $decryptData, $pr_key);
		$crypto .= $decryptData;
	}
	$array = array();
	$array = json_decode($crypto,1);

	$mymoney = number_format($array['amount']/100, 2, '.', ''); //订单金额
	$success_msg = $array['payStateCode'];//成功讯息
	$success_code = "00";//文档上的成功讯息
	$sign = $array['sign'];;//签名
	$echo_msg = "SUCCESS";//回调讯息

	ksort($array);
	$sign_array = array();
	foreach ($array as $k => $v) {
		if ($k !== 'sign'){
			$sign_array[$k] = $v;
			// write_log($k . "=" . $v);
		}
	}
	$signtext = json_encode($sign_array,320) . $md5key;
	$mysign =  strtoupper(md5($signtext));

	// write_log("signtext=".$signtext);
	// write_log("mysign=".$mysign);


	#到账判断
	if ($success_msg == $success_code) {
	if ($mysign == $sign) {
			$result_insert = update_online_money($order_no, $mymoney);
			if ($result_insert == -1) {
				$message = ("会员信息不存在，无法入账");
			}else if($result_insert == 0){
				$message = ("支付成功");
			}else if($result_insert == -2){
				$message = ("数据库操作失败");
			}else if($result_insert == 1){
				$message = ("支付成功");
			} else {
				$message = ("支付失败");
			}
		}else{
			$message = ('签名不正确！');
		}
	}else{
		$message = ("交易失败");
	}
}else{
	$message = ("支付成功");
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
		<tr>
			<td style="width: 120px; text-align: right;">充值金额：</td>
			<td style="padding-left: 10px;">
				<label id="lbpayamount"><?php echo $mymoney; ?></label>
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
			<td style="width: 120px; text-align: right;">备注</td>
			<td style="padding-left: 10px;">
				<label id="lbmessage">该页面仅作为通知用，若与支付平台不相符时，则以支付平台结果为准</label>
			</td>
		</tr>
		
	</table>
</body>
</html>
