<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>跳转中...</title>
</head>
<?php
/* *
 * 功能：即时到账交易接口接入页
 * 
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */

require_once("epay.config.php");
require_once("gateway.php");
/**************************请求参数**************************/
        //需http://格式的完整路径，不能加?id=123这类自定义参数
        $notify_url = "http://demo1.jdszm.cn/notify_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url = "http://demo1.jdszm.cn/return_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //商户订单号
        $out_trade_no = $_POST['WIDout_trade_no'];
        //商户网站订单系统中唯一订单号，必填

		//支付方式
        $type = $_POST['type'];
  		//key
  		$key=$alipay_config['key'];
  		$paytype='1';
  		if($type == '1'){
          	$paytype='2';
        }else if($type == '2'){
          	$paytype='1';
        }else if($type == '3'){
          	$paytype='3';
        }
        //商品名称
        $name = $_POST['WIDsubject'];
		//付款金额
        $money = $_POST['WIDtotal_fee'];
		//站点名称
        $sitename = 'SAF支付测试站点';
        //签名sign
  		$sign= md5(floatval($money) . trim($out_trade_no) . $key);


/************************************************************/

$param="sdk=&record={$out_trade_no}&money={$money}&type=json&notify_url={$notify_url}&refer={$return_url}&sign={$sign}&paytype={$paytype}&key={$key}";
$gatewayurl=$alipay_config['apiurl'];
topay($gatewayurl,$param,$type,$return_url);
?>
</body>
</html>