<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
// include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");

//解密
function decode($data,$private){   
	//读取秘钥
	$pr_key = openssl_pkey_get_private($private);
	if ($pr_key == false){
		echo "打开密钥出错";
		die;
	}
	$data = base64_decode($data);
	// write_log('data=' . $data);
	$crypto = '';
	//分段解密   
	foreach (str_split($data, 128) as $chunk) {
		openssl_private_decrypt($chunk, $decryptData, $pr_key);
		$crypto .= $decryptData;
	}
	// write_log('crypto=' . $crypto);
	return $crypto;
}

#接收资料
#REQUEST方法
$data = array();
foreach ($_REQUEST as $key => $value) {
	$data[$key] = $value;
	// write_log("return:".$key."=".$value);
}
$manyshow = 0;
if(!empty($data)){
	$manyshow = 1;
	#设定固定参数
	$order_no = $data['orderNo']; //订单号
	$success_code = "00";//文档上的成功讯息
	$echo_msg = "SUCCESS";//回调讯息

	#根据订单号读取资料库
	$params = array(':m_order' => $order_no);
	$sql = "select operator from k_money where m_order=:m_order";
	$stmt = $mydata1_db->prepare($sql);
	// $stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
	$stmt->execute($params);
	$row = $stmt->fetch();

	#获取该订单的支付名称
	$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
	$params = array(':pay_type' => $pay_type);
	$sql = "select * from pay_set where pay_type=:pay_type";
	$stmt = $mydata1_db->prepare($sql);
	// $stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
	$stmt->execute($params);
	$payInfo = $stmt->fetch();
	$pay_mid = $payInfo['mer_id'];
	$pay_mkey = $payInfo['mer_key'];
	$pay_account = $payInfo['mer_account'];
	$accountexp = explode('###',$pay_account);
	$pay_md5key = $accountexp[0];
	$pay_account = $accountexp[1];//商户公钥
	if ($pay_mid == "" || $pay_mkey == "") {
		echo "非法提交参数";
		exit;
	}

	#私钥加头尾
	$private_key = "-----BEGIN PRIVATE KEY-----\r\n";
	foreach (str_split($pay_mkey,64) as $str){
		$private_key .= $str . "\r\n";
	}
	$private_key .="-----END PRIVATE KEY-----";
	#RSA解密
	$resultdata = $data['data'];
	$resultJson=decode($resultdata,$private_key);
	#验签方式
	$resultarray = json_decode($resultJson,1);
	$sign = $resultarray['sign'];//签名
	$success_msg = $resultarray['payStateCode'];//成功讯息
	write_log("success_msg=".$success_msg);
	$mymoney = number_format($resultarray['amount']/100, 2, '.', ''); //订单金额
	write_log("mymoney=".$mymoney);
	ksort($resultarray);
	$signtext = array();
	foreach ($resultarray as $key => $value) {
		if($key !== 'sign'){
			$signtext[$key] = $value;
		}
	}
	$mysign = strtoupper(md5(json_encode($signtext,320) . $pay_md5key));//签名
	// write_log("mysign=".$mysign);

	#到账判断
	if ($success_msg == $success_code) {
	if ( $mysign == $sign) {
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
