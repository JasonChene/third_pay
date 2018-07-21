<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
include_once("./function.php");

// write_log("notify");
// #
// write_log("REQUEST方法");
// foreach ($_REQUEST as $key => $value) {
// 	write_log($key."=".$value);
// }
// write_log("POST方法");
// foreach ($_POST as $key => $value) {
// 	write_log($key."=".$value);
// }
// #input方法
// write_log('input方法');
// $input_data=file_get_contents("php://input");
// write_log($input_data);

$data = json_decode($_POST['reqJson'],1);
// foreach ($data as $key => $value) {
// 	write_log($key."=".$value);
// }
$params = array(':m_order' => $data['extra_para']);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
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
$echos=array(
	"respCode" => "SUCCESS"
);
$str = sortData($echos);
$baseStr = base64_encode($str);
$aesPrivage = encrypt($baseStr, $pay_account,'AES-128-ECB');
$aesPrivage = strtoupper($aesPrivage);
$sign = strtoupper(md5($aesPrivage . $pay_mkey));
$echos['sign'] = $sign;

$echosuccess = json_encode($echos);
$vdata = VerifySign($data['transData'],$pay_account,$pay_mkey);//含验签

if ($vdata != false) {
  if ( $vdata['isClearOrCancel'] == "0") {
  	$mymoney = number_format($vdata['totalAmount'], 2, '.', ''); //订单金额
  	// write_log("验签成功");
		$result_insert = update_online_money($data['extra_para'], $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			exit;
		}else if($result_insert == 0){
			echo ($echosuccess);
			// write_log("SUCCESS");
			exit;
		}else if($result_insert == -2){
			echo ("数据库操作失败");
			exit;
		}else if($result_insert == 1){
			echo ($echosuccess);
			// write_log("SUCCESS");
			exit;
		} else {
			echo ("支付失败");
			exit;
		}
	}else{
		echo ("交易失败");
		exit;
	}
}else{
	echo ('签名不正确！');
	exit;
}

?>
