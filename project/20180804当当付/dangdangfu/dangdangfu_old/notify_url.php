<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
//include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

$amount = trim($_REQUEST["amount"]);
$traceno = trim($_REQUEST["traceno"]);
$status = trim($_REQUEST["status"]);
$signature = trim($_REQUEST["signature"]);

$params = array(':m_order' => $traceno);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);
//$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
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



$post_data = array(
"transDate" => $_REQUEST['transDate'],
"transTime" =>$_REQUEST['transTime'],
"merchno" =>$_REQUEST['merchno'],
"merchName" =>$_REQUEST['merchName'],
"customerno" =>$_REQUEST['customerno'],
"openId"=>$_REQUEST['openId'],
"amount" =>$_REQUEST['amount'],
"traceno" => $_REQUEST['traceno'],
"payType" =>$_REQUEST['payType'],
"orderno" =>$_REQUEST['orderno'],
"channelOrderno" => $_REQUEST['channelOrderno'],
"channelTraceno" =>$_REQUEST['channelTraceno'],
"status" =>$_REQUEST['status']
);

//$file = "log.txt";


ksort($post_data);
$signText = "";
foreach($post_data as $key=>$value){
	if($value != "" && $value != null && $value != "null"){
			$signText .= $key . "=" . $value . "&";
	}
}

$sign = strtoupper(md5($signText . $pay_mkey));

$success = 0;

if($_REQUEST["transDate"]){
    if ($status == "1") {
        $success = 1;
    }
}else {
    if ($status = "2") {
        $success = 1;
    }
}
//file_put_contents($file,"\r\n==signText==".$signText . $pay_mkey,FILE_APPEND);
//file_put_contents($file,"\r\n==sign==".$sign,FILE_APPEND);
//file_put_contents($file,"\r\n==signature==".$signature,FILE_APPEND);
if ($success) {
  if ($signature == $sign) {
	  	$myMoney = $amount;
		$result_insert = update_online_money($traceno, $myMoney);
//file_put_contents($file,"\r\n==result_insert==".$result_insert,FILE_APPEND);
		if ($result_insert == -1) {
            echo "会员信息不存在，无法入账";
		} else if ($result_insert == 0) {
			echo "success";
		} else if ($result_insert == -2) {
            echo "数据库操作失败";
		} else if ($result_insert == 1) {
            echo "success";
		} else {
            echo "支付失败";
		}
	} else {
        echo '签名不正确！';
		exit;
	}
} else {
    echo '交易失败！';
	exit;
}

?>
