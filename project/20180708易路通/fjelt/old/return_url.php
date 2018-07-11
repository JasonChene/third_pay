<? header("content-Type: text/html; charset=utf-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
//include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

$data = array();
#接收资料
$input_data=file_get_contents("php://input");
$res=json_decode($input_data,1);//json回传资料
foreach ($res as $key => $value) {
	$data[$key] = $value;
	//write_log($key."=".$value);
}

#设定固定参数
$order_no = $data['BusinessOrders']; //订单号
$mymoney = number_format($data['Amount']/100, 2, '.', ''); //订单金额
$success_msg = $data['OrderStatus'];//成功讯息
$success_code = "SUCCESS";//文档上的成功讯息(根本沒有)
$sign = base64_decode($data['Sign']);//签名
$echo_msg = "SUCCESS";//回调讯息

#根据订单号读取资料库
$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);
//$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

#获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
//$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}

#验签方式
$public_pem = chunk_split($pay_account,64,"\r\n");//转换为pem格式的公钥
$public_pem = "-----BEGIN PUBLIC KEY-----\r\n".$public_pem."-----END PUBLIC KEY-----\r\n";
$private_pem = chunk_split($pay_mkey,64,"\r\n");//转换为pem格式的私钥
$private_pem = "-----BEGIN PRIVATE KEY-----\r\n".$private_pem."-----END PRIVATE KEY-----\r\n";
//write_log("public_pem=".$public_pem);
ksort($data);
$noarr =array('Sign');
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if ( !in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val ===0 || $arr_val ==='0') ) {
		$signtext .= $arr_key.'='.$arr_val.'&';
	}
}
$signtext = substr($signtext,0,-1);
//write_log("signtext=".$signtext);
$PublicKey = openssl_get_publickey($public_pem);
if ($PublicKey == false) {
	echo "打开公钥出错";
	//write_log("打开公钥出错=".$PublicKey);
	exit;
}
$va = openssl_verify($signtext, $sign, $PublicKey, OPENSSL_ALGO_MD5);
if($va != 1) {
  echo "数据校验不通过";
  //write_log("数据校验不通过=".$va);
  exit;
}

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
		
	</table>
</body>
</html>
