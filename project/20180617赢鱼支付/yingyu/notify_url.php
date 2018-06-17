<? header("content-Type: text/html; charset=UTF-8");?>
<?php

include_once("../config.php");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
include_once("utils.php");

$get_data = file_get_contents("php://input");
$data = json_decode($get_data);

$orderno = $data->outTradeNo; //网站支付的订单号

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

$partner = $pay_mid;//商户ID
$appKey = $pay_mkey;//商户KEY

   if ($data->resultCode == '00' && $data->resCode == '00') {

        $resultToSign = array();

        foreach ($data as $key => $value) {
            if ($key != 'sign') {
                $resultToSign[$key] = $value;
            }
        }

        $str = formatBizQueryParaMap($resultToSign);
        $resultSign = strtoupper(md5($str."&key=".$appKey));

	if ($resultSign == $data->sign){

		if($data->status == "02"){

			$result_insert = update_online_money($orderno,($data->orderAmt)/100);
			if ($result_insert==-1) {
				echo "11";
				exit;
			} else if ($result_insert==0) {
				echo "00";
				exit;
			} else if ($result_insert==-2) {
				echo "11";
				exit;
			} else if ($result_insert==1) {
				echo "00";
				exit;
			} else {
				echo("支付失败");
			}
		}
		else 
		{
			echo("支付失败");
		}	
	}else{
		$result="Signature Error";
	}
   }
?>