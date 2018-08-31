<?php
header("Content-type:text/html; charset=utf-8"); 

//include_once("../config.php");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

$partner = trim($_REQUEST['partner']);
$ordernumber = trim($_REQUEST['ordernumber']);
$orderstatus = trim($_REQUEST['orderstatus']);
$paymoney = trim($_REQUEST['paymoney']);
$sysnumber = trim($_REQUEST['sysnumber']);
$attach = trim($_REQUEST['attach']);
$sign = trim($_REQUEST['sign']);

$params = array(':m_order'=>$ordernumber);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$payType = substr($row['operator'] , 0 , strripos($row['operator'],"_"));

$params = array(':pay_type'=>$payType);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
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
$curdata = explode("|" , $attach);

if(md5($curdata[0].$pay_mid.$curdata[1]) != $curdata[2])
{
	echo '签名不正确！';
	exit;	
}

$signText = 'partner=' . $partner  . '&ordernumber=' . $ordernumber . '&orderstatus=' . $orderstatus . '&paymoney='  . $paymoney .$pay_mkey;

$signValue = strtolower(md5($signText));


if ($signValue == $sign)
{
	//----------------------------------------------------
	//  判断交易是否成功
	//  See the successful flag of this transaction
	//----------------------------------------------------
	if ($orderstatus == '1')
	{       
        /* 会员入款 开始 */
		$result_insert = update_online_money($ordernumber,$paymoney);
		if ($result_insert==-1) {
			echo("会员信息不存在，无法入账");
		} else if ($result_insert==0) {
			echo("ok");
		} else if ($result_insert==-2) {
			echo("数据库操作失败");
		} else if ($result_insert==1) {
			echo("ok");
		} else {
			echo("支付失败");
		}
        /* 会员入款 结束 */
	}
	else
	{
		echo '交易失败！';
		exit;
	}
}
else
{
	echo '签名不正确！';
	exit;
}
?>