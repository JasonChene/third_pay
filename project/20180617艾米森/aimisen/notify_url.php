<?php session_start(); ?>
<?php
include_once("../config.php");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

$post_data = array(
"src_code" => $_POST['src_code'],
"trade_no" =>$_POST['trade_no'],
"out_trade_no" =>$_POST['out_trade_no'],
"time_start" =>$_POST['time_start'],
"pay_time"=>$_POST['pay_time'],
"total_fee" =>$_POST['total_fee'],
"trade_type" => $_POST['trade_type'],
"fee_type" =>$_POST['fee_type'],
"goods_name" =>$_POST['goods_name'],
"goods_detail" => $_POST['goods_detail'],
"order_status" =>$_POST['order_status'],
"order_type" =>$_POST['order_type'],
"cancel" =>$_POST['cancel'],
"out_mchid" =>$_POST['out_mchid'],
"mchid" =>$_POST['mchid'],
"orig_trade_no" =>$_POST['orig_trade_no'],
"time_expire" =>$_POST['time_expire'],
);
$orderno = $_POST['out_trade_no'];

$order_amount = $_POST['total_fee'];
$params = array(':m_order'=>$_POST['out_trade_no']);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$payType = substr($row['operator'] , 0 , strripos($row['operator'],"_"));

$params = array(':pay_type'=>$payType);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];
if($pay_mid == "" || $pay_mkey == ""){
	echo "非法提交参数";
	exit;
}

//签名操作
ksort($post_data);

$a='';
foreach($post_data as $x=>$x_value){
	if($x_value){
			$a=$a.$x."=".$x_value."&";
	}
}
$ea_key = $pay_mkey;

$b = md5($a.'key='.$ea_key);
$c=$_POST['sign'];


$d=strtoupper($b);
if($d==$c){

	$result_insert = update_online_money($_POST['out_trade_no'],($order_amount/100));
	if ($result_insert==-1) {
		//echo("会员信息不存在，无法入账");
		exit;
	} else if ($result_insert==0) {
		echo "SUCCESS";
		exit;
	} else if ($result_insert==-2) {
		//echo("数据库操作失败");
		exit;
	} else if ($result_insert==1) {
		echo "SUCCESS";
		exit;
	} else {
		//echo("支付失败");
		exit;
	}
}else{
	echo "fail";
	$myfile = fopen("payBack.log", "a") or die("Unable to open file!");
	fwrite($myfile, date('y-m-d H:i:s',time())."返回的数据:"."fail\n");
	fclose($myfile);
}

?>