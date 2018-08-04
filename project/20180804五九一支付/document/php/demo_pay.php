<?php	
header("Content-type:text/html;charset=utf-8");
$app_id="1804280007"; 								//商户ID 在商户中心获取
$key="4c76d1cdc4db6dfdeb461c9e79029096"; 								//商户密钥 在商户中心获取
$interface_version="V2.0"; 						//接口版本，默认值"V2.0"
$trade_type=$_POST['trade_type'];  					//通道类型（例：支付宝扫码（ALIPAY_NATIVE））请参照开发文档 3支付类型代码对照表
$total_amount=$_POST['total_amount']*100;							//订单金额，单位为分
$out_trade_no="2018031915553234".time();   			//订单号，需唯一
$notify_url=$_POST['notify_url'];  	//异步通知地址
$return_url=$_POST['return_url'];  	//同步返回地址
$extra_return_param = $_POST['extra_return_param']; 					//备注信息 有中文需要编码，回调时原样返回
$client_ip = "127.0.0.1";						//提交Ip 可为空
$posturl = "http://cpay.pay591.com/pay/gateway"; //提交地址

$sign ="app_id=".$app_id."&notify_url=".$notify_url."&out_trade_no=".$out_trade_no."&total_amount=".$total_amount."&trade_type=".$trade_type; 
$sign=md5($sign.$key);  
$PostUrl=$posturl."?app_id=".$app_id."&interface_version=".$interface_version."&trade_type=".$trade_type."&total_amount=".$total_amount."&out_trade_no=".$out_trade_no."&return_url=".$return_url."&notify_url=".$notify_url."&extra_return_param=".$extra_return_param."&client_ip=".$client_ip."&sign=".$sign;

//跳转到指定网站
if (isset($PostUrl)) 
   { 
       header("Location: $PostUrl"); 
       exit;
   }else{
		echo "<script type='text/javascript'>";
		echo "window.location.href='$PostUrl'";
		echo "</script>";	
};
?>
