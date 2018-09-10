<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

$data = array();
foreach ($_GET as $key => $value) {
	$data[$key] = $value;
	//write_log($key."=".$value);
}
$params = array(':m_order' => $data['order_no']);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));

$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];

if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}
//$ts = time();
$ts = $data['ts'];
$signtext='app_id='.$data['app_id'].'is_success='.$data['is_success'].'order_no='.$data['order_no'].'pay_actual_amt='.$data['pay_actual_amt'].$ts.$pay_mkey;
//write_log("signtext=".$signtext);
$mysign = md5($signtext);
//write_log("mysign=".$mysign);

if ($data['is_success'] == "1") {
  if ( $mysign == $data['sign']) {
  	$mymoney = number_format($data['pay_actual_amt'], 2, '.', ''); //订单金额
//write_log("mymoney=".$mymoney);
		$result_insert = update_online_money($data['order_no'], $mymoney);
//write_log("result_insert=".$result_insert);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			exit;
		}else if($result_insert == 0){
			echo ("ok");
			exit;
		}else if($result_insert == -2){
			echo ("数据库操作失败");
			exit;
		}else if($result_insert == 1){
			echo ("ok");
			exit;
		} else {
			echo ("支付失败");
			exit;
		}
	}else{
		echo '签名不正确！';
		exit;
	}
}else{
	echo ("交易失败");
	exit;
}

?>
