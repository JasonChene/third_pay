<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
// include_once("../../../database/mysql.config.php");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
// write_log("notify");


#接收资料
#post方法
$data = array();
foreach ($_POST as $key => $value) {
	$data[$key] = $value;
	// write_log($key . "=" . $value);
}

#设定固定参数
$order_no = $data['data']['agentorderno']; //订单号
// write_log($order_no);
$mymoney = number_format($data['data']['totalamount'], 2, '.', ''); //订单金额
$echo_msg = "SUCCESS";//回调讯息

#根据订单号读取资料库
$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();

#获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$payInfo = $stmt->fetch();
$mer_arr = explode('###',$payInfo['mer_id']);
$mchid = $mer_arr[0];//商户号
// write_log($mchid);
$submchid = $mer_arr[1];//子商户号
$pay_mkey = $payInfo['mer_key'];//商戶私钥
$pay_account = $payInfo['mer_account'];
if ($mchid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}

#验签方式
function SortToString($data){
	ksort($data);
	$temp = [];
	foreach($data as $i => $v){
	if(isset($v)){
		if(is_array($v)){
			$temp[] = $i . "=" . $this->SortToString($v);
			}else{
				$temp[] = $i . "=" . $v;
			}
		}
	}
	return join("&", $temp);
}



#到账判断
if ($data['code'] == '1' && $data['data']['status'] == "1") {
	$verify_data = $data;
	unset($verify_data['sign']);
	$verify_data['data'] = SortToString($verify_data['data']);
	$verify_data = (SortToString($verify_data));
	// write_log($verify_data);
	$verify = openssl_verify($verify_data, base64_decode($data['sign']), openssl_pkey_get_public($pay_account));
	// write_log($verify);
	if ($verify) {
		$result_insert = update_online_money($order_no, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			// write_log("会员信息不存在，无法入账");
			exit;
		} else if ($result_insert == 0) {
			echo ($echo_msg);
			// write_log($echo_msg . 'at 0');
			exit;
		} else if ($result_insert == -2) {
			echo ("数据库操作失败");
			// write_log("数据库操作失败");
			exit;
		} else if ($result_insert == 1) {
			echo ($echo_msg);
			// write_log($echo_msg . 'at 1');
			exit;
		} else {
			echo ("支付失败");
			// write_log("支付失败");
			exit;
		}
	} else {
		echo ('签名不正确！');
		// write_log("签名不正确！");
		exit;
	}
} else {
	echo ("交易失败");
	// write_log("交易失败");
	exit;
}

?>
