<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
function verity($key,$data,$signature)  
{  
  $public_pem = openssl_get_publickey($key);//签名秘钥
	$result = openssl_verify($data, base64_decode($signature), $public_pem);  
	return $result;  
}

// write_log("notify");

#接收资料
#input方法
$result = file_get_contents("php://input");
// write_log($result);
//资料处理
$tmp = explode("|", $result);
$resp_xml = base64_decode($tmp[0]);
$resp_sign = $tmp[1];
$rep1 = explode('<',$resp_xml);
$rep2_1 = explode('>',$rep1[2]);
$rep2_2 = explode('/>',$rep1[4]);
$rep3_1 = substr($rep2_1[0],0,-1);
$rep3_2 = substr($rep2_2[0],0,-1);
$newreparr = explode(' ',$rep3_1);
$newreparr2 = explode(' ',$rep3_2);
$data = array();
foreach($newreparr as $reparr_key => $reparr_value){
  $newdata = explode('=',$reparr_value,2);
  $data[$newdata[0]] = substr($newdata[1],1,-1);
}
foreach($newreparr2 as $reparr_key => $reparr_value){
	$newdata = explode('=',$reparr_value,2);
	$data[$newdata[0]] = substr($newdata[1],1,-1);
}
unset($data['message']);
unset($data['item']);
// foreach ($data as $key => $value) {
// 	write_log($key."=".$value);
// }
//资料处理END
#设定固定参数
$order_no = $data['merchantOrderId']; //订单号
$mymoney = number_format($data['payAmt']/100, 2, '.', ''); //订单金额
$success_msg = $data['payStatus'];//成功讯息
$success_code = "01";//文档上的成功讯息
$sign = $data['sign'];//签名
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
$signsuccess = verity($pay_account,MD5($resp_xml,1),$resp_sign);
// write_log((int)$signsuccess);
#到账判断
if ($success_msg == $success_code) {
  if ( (int)$signsuccess == 1) {
		$result_insert = update_online_money($order_no, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			write_log("会员信息不存在，无法入账");
			exit;
		}else if($result_insert == 0){
			echo ($echo_msg);
			write_log($echo_msg.'at 0');
			exit;
		}else if($result_insert == -2){
			echo ("数据库操作失败");
			write_log("数据库操作失败");
			exit;
		}else if($result_insert == 1){
			echo ($echo_msg);
			write_log($echo_msg.'at 1');
			exit;
		} else {
			echo ("支付失败");
			write_log("支付失败");
			exit;
		}
	}else{
		echo ('签名不正确！');
		write_log("签名不正确！");
		exit;
	}
}else{
	echo ("交易失败");
	write_log("交易失败");
	exit;
}

?>
