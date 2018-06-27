<? header("content-Type: text/html; charset=UTF-8");?>
<?php
//include_once("../config.php");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");


//$file = "log.txt";

$orderno = $_REQUEST["outorderno"]; //网站支付的订单号
//file_put_contents($file,"\r\n==orderno==".$orderno,FILE_APPEND);
$params = array(':m_order'=>$orderno);
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

if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}
$data = array();
$data['status'] = $_REQUEST["status"]; 
$data['extraparam'] = $_REQUEST["extraparam"];
$data['charset'] = $_REQUEST["charset"];
$data['transactionid'] = $_REQUEST["transactionid"];
$data['outtransactionid'] = $_REQUEST["outtransactionid"];
$data['outorderno'] = $_REQUEST["outorderno"];
$data['totalfee'] = $_REQUEST["totalfee"];
$data['mchid'] = $pay_mid;
$sign = $_REQUEST["sign"];

/*
file_put_contents($file,"\r\n==status==".$data['status'],FILE_APPEND);
file_put_contents($file,"\r\n==extraparam==".$data['extraparam'],FILE_APPEND);
file_put_contents($file,"\r\n==charset==".$data['charset'],FILE_APPEND);
file_put_contents($file,"\r\n==transactionid==".$data['transactionid'],FILE_APPEND);
file_put_contents($file,"\r\n==outtransactionid==".$data['outtransactionid'],FILE_APPEND);
file_put_contents($file,"\r\n==outorderno==".$data['outorderno'],FILE_APPEND);
file_put_contents($file,"\r\n==totalfee==".$data['totalfee'],FILE_APPEND);
file_put_contents($file,"\r\n==mchid==".$data['mchid'],FILE_APPEND);
*/
$temp='';
ksort($data);//对数组进行排序
//遍历数组进行字符串的拼接
foreach ($data as $x=>$x_value){
    if ($x_value != null&&$x_value != "null"&&$x_value != ""){
        $temp = $temp.$x."=".$x_value."&";
    }
}


//file_put_contents($file,"\r\n==temp==".$temp."key=".$pay_mkey,FILE_APPEND);
	$signture=strtoupper(md5($temp."key=".$pay_mkey));


//file_put_contents($file,"\r\n==sign==".$sign,FILE_APPEND);
//file_put_contents($file,"\r\n==signture==".$signture,FILE_APPEND);

if ($signture == $sign){
		if($data['status'] == '100'){
			$result_insert = update_online_money($orderno,$data['totalfee']/100);
//file_put_contents($file,"\r\n==result_insert==".$result_insert,FILE_APPEND);
			if ($result_insert==-1) {
				echo "fail";
				exit;
			} else if ($result_insert==0) {
				echo "SUCCESS";
				exit;
			} else if ($result_insert==-2) {
				echo "fail";
				exit;
			} else if ($result_insert==1) {
				echo "SUCCESS";
				exit;
			} else {
				echo("fail");
			}
		}
		else 
		{
			echo("fail");
		}	
	}else{
		$result="Signature Error";
	}

?>
