<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
/* *
 功能：银宝页面跳转异步通知页面
 版本：3.0
 日期：2013-08-01
 说明：
 以下代码仅为了方便商户安装接口而提供的样例具体说明以文档为准，商户可以根据自己网站的需要，按照技术文档编写。
 * */
//include_once("../config.php");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

$arr = array();
//获取GET过来反馈信息
$arr['orderid'] = $_REQUEST["orderid"];
$arr['opstate'] = $_REQUEST["opstate"];
$arr['ovalue'] = $_REQUEST["ovalue"];
$arr['systime'] = $_REQUEST["systime"];
$arr['sign'] = $_REQUEST["sign"];
$arr['sysorderid'] = $_REQUEST["sysorderid"];
$arr['completiontime'] = $_REQUEST["completiontime"];
$arr['attach'] = $_REQUEST['attach'];
$arr['msg'] = $_REQUEST['msg'];


//根据订单号查找用户的UID和支付信息
$params = array(':m_order' => $arr['orderid']);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$payType = substr($row['operator'], 0, strripos($row['operator'], "_"));

$params = array(':pay_type' => $payType);
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
$curdata = explode("|", $arr['attach']);
if (md5($curdata[0] . $pay_mid . $curdata[1]) != $curdata[2]) {
	echo '签名不正确！';
	exit;
}

$buff = "orderid=" . $arr['orderid'] . "&pstate=" . $arr['opstate'] . "&ovalue=" . $arr['ovalue'] . $pay_mkey;
	
// 计算签名
$sign = md5($buff);

if ($sign == $arr['sign']) {
	//验签成功
	/**
	此处进行商户业务操作
	业务结束
	 */
	if ($arr['opstate'] == "0") //交易成功
	{
		echo "会员帐号：" . $curdata[0] . "成功充值 " . $arr['ovalue'] . " 元，订单号： " . $arr['orderid'];
	} else {
		echo "会员帐号：" . $curdata[0] . "充值失败 " . $arr['ovalue'] . " 元，订单号： " . $arr['orderid'];
	}
} else {
	//验签失败 业务结束
	echo "会员帐号：" . $curdata[0] . "充值失败 " . $arr['ovalue'] . " 元，订单号： " . $arr['orderid'];

}
?>
