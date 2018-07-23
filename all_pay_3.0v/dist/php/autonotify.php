
<?php
$req = json_decode(file_get_contents('php://input'),1);
$key = json_decode($req['key'], true);
$req = json_decode($req['data'], true);
echo 'header("content-Type: text/html; charset=UTF-8");'."\n";
if ($key == 1) {
    echo 'include_once("../../../database/mysql.config.php");'."\n";
}else {
    echo 'include_once("../../../database/mysql.php");'."\n";
}

echo 'include_once("../moneyfunc.php");';
echo '#write_log("notify")';
if (condition) {
    echo "write_log(request方法);";
    echo 'foreach ($_REQUEST as $key => $value) {'."\n";
    echo '$data[$key] = $value;'."\n";
    echo 'write_log($key."=".$value);'."\n";
    echo '}'."\n";
}elseif (condition) {
    echo "write_log(post方法);";
    echo 'foreach ($_REQUEST as $key => $value) {'."\n";
    echo '$data[$key] = $value;'."\n";
    echo 'write_log($key."=".$value);'."\n";
    echo '}'."\n";
}elseif (condition) {
    echo "write_log(get方法);";
    echo 'foreach ($_REQUEST as $key => $value) {'."\n";
    echo '$data[$key] = $value;'."\n";
    echo 'write_log($key."=".$value);'."\n";
    echo '}'."\n";
}elseif (condition) {
    echo "write_log(input方法);";
    echo '$input_data=file_get_contents("php://input");'."\n";
    echo 'write_log($input_data);';
    echo '$res=json_decode($input_data,1);//json回传资料'."\n";
    echo 'foreach ($res as $key => $value) {'."\n";
    echo '$data[$key] = $value;'."\n";
    echo 'write_log($key."=".$value);'."\n";
    echo '}'."\n";
}

#设定固定参数
echo '$order_no = $data[\"order_no\"]; //订单号'."\n";
echo '$mymoney = number_format($data[\"pay_amoumt\"], 2, ".", ""); //订单金额'."\n";
$success_msg = $data['is_success'];//成功讯息
$success_code = "1";//文档上的成功讯息
$sign = $data['sign'];//签名
$echo_msg = "";//回调讯息

#根据订单号读取资料库
$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();

#获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);//现数据库的连接方式
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
$noarr = array('sign');//不加入签名的array key值
ksort($data);
$signtext = "";
foreach ($data as $arr_key => $arr_val) {
	if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val ===0 || $arr_val ==='0')) {
		$signtext .= $arr_key . '=' . $arr_val . '&';
	}
}
$signtext = substr($signtext, 0,-1);//验签字串
//write_log("signtext=".$signtext);
$mysign = md5($signtext);//签名
//write_log("mysign=".$mysign);

#验签方式2
$signtext = "";
$signtext .= 'order_no='.$data['order_no'].'&';
$signtext .= 'pay_amoumt='.$data['pay_amoumt'].'&';
$signtext .= 'is_success='.$data['is_success'];
//write_log("signtext=".$signtext);
$mysign = md5($signtext);//签名
//write_log("mysign=".$mysign);

#到账判断
if ($success_msg == $success_code) {
  if ( $mysign == $sign) {
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
