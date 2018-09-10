<?php header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
// include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

// write_log('notify:');

// write_log("in");
$ret = $_REQUEST['ret'];
$msg = $_REQUEST['msg'];
$arr_ret = json_decode($ret, true);
$arr_msg = json_decode($msg, true);
$result = $arr_ret["code"];//支付结果
$pay_message = $arr_ret["msg"]; //支付结果消息，支付成功为空
$orderno = $arr_msg['no']; //网站支付的订单号
$amount = $arr_msg['money']; //支付总金额


// write_log("arr_ret:");
// foreach ($arr_ret as $key => $value) {
//     write_log($key . "=" . $value);
// }

// write_log("arr_msg:");
// foreach ($arr_msg as $key => $value) {
//     write_log( $key . "=" . $value);
// }



$params = array(':m_order'=>$orderno);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);
// $stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$payType = substr($row['operator'] , 0 , strripos($row['operator'],"_"));

$params = array(':pay_type'=>$payType);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
// $stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];
if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}
// write_log("sql ok");


$public_key = $pay_account;
// write_log($public_key);
$pu_key = '';

if(openssl_pkey_get_public($public_key)){
    $pu_key = openssl_pkey_get_public($public_key);
    $datas = stripslashes($_REQUEST['ret'].'|'.$_REQUEST['msg']);
	//验签
    $txt = openssl_verify($datas,base64_decode($_REQUEST['sign']),$pu_key);
    // write_log("txt=" . $txt);

    openssl_free_key($pu_key);
    
    // write_log("get key ok");
	if(1==$txt){
        $myMoney = number_format($amount/100, 2, '.', '');
        $result_insert = update_online_money($orderno,$myMoney);
        // write_log("resultInsert=".$result_insert);
		if ($result_insert==-1) {
			echo("会员信息不存在，无法入账");
		} else if ($result_insert==0) {
            echo stripslashes('SUCCESS');
            // write_log("insert ok 0");
		} else if ($result_insert==-2) {
			echo("数据库操作失败");
		} else if ($result_insert==1) {
            echo stripslashes('SUCCESS');
            // write_log("insert ok 1");
		} else {
			echo("支付失败");
		}
	}
}else{
    echo $arr_ret["msg"];
    // write_log($arr_ret["msg"]);
}


?>