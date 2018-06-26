<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
#function
function HmacMd5($data,$key)
{

$key = iconv("GB2312","UTF-8",$key);
$data = iconv("GB2312","UTF-8",$data);

$b = 64;
if (strlen($key) > $b) {
$key = pack("H*",md5($key));
}
$key = str_pad($key, $b, chr(0x00));
$ipad = str_pad('', $b, chr(0x36));
$opad = str_pad('', $b, chr(0x5c));
$k_ipad = $key ^ $ipad ;
$k_opad = $key ^ $opad;

return md5($k_opad . pack("H*",md5($k_ipad . $data)));
}
#接收资料
#post方法
$data = array();
foreach ($_GET as $key => $value) {
	$data[$key] = $value;
	//write_log($key."=".$value);
}

#设定固定参数
$order_no = $data['r6_Order']; //订单号
$mymoney = number_format($data['r3_Amt'], 2, '.', ''); //订单金额
$success_msg = $data['r1_Code'];//成功讯息
$success_code = "1";//文档上的成功讯息
$sign = $data['hmac'];//签名
$echo_msg = "success";//回调讯息

#根据订单号读取资料库
$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

#获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
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
$noarr = array('hmac','rb_BankId','ro_BankOrderId','rp_PayDate','rq_CardNo','ru_Trxtime');//不加入签名的array key值
$signtext="";
foreach ($data as $arr_key => $arr_val) {
	if (!in_array($arr_key, $noarr)) {
		$signtext .= $arr_val;
	}
}
//write_log("signtext=".$signtext);//验签字串
$hmac = HmacMd5($signtext,$pay_mkey);
$mysign = $hmac;//签名
//write_log("mysign=".$mysign);

#到账判断
if ($success_msg == $success_code) {
  if ( $mysign == $sign) {
		$result_insert = update_online_money($order_no, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			//write_log("会员信息不存在，无法入账");
			exit;
		}else if($result_insert == 0){
			echo ($echo_msg);
			//write_log($echo_msg.'at 0');
			exit;
		}else if($result_insert == -2){
			echo ("数据库操作失败");
			//write_log("数据库操作失败");
			exit;
		}else if($result_insert == 1){
			echo ($echo_msg);
			//write_log($echo_msg.'at 1');
			exit;
		} else {
			echo ("支付失败");
			//write_log("支付失败");
			exit;
		}
	}else{
		echo ('签名不正确！');
		//write_log("签名不正确！");
		exit;
	}
}else{
	echo ("交易失败");
	//write_log("交易失败");
	exit;
}

?>
