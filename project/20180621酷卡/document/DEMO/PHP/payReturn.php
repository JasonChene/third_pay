<!DOCTYPE html>
<?php
header("Content-type: text/html; charset=utf-8");

require("info.php");
		$orderid=$_GET['orderid'];//商户订单号
		$opstate=$_GET['opstate']; //0表示支付成功
		$ovalue=$_GET['ovalue'];//订单金额
		$sysorderid=$_GET['sysorderid'];//酷卡订单号
		$systime=$_GET['systime'];//酷卡处理完时间
		$attach=$_GET['attach'];//备注消息
		$sign=$_GET['sign'];//加密密文
		
		//准备加密字符串
		$signStr="orderid={$orderid}&opstate={$opstate}&ovalue={$ovalue}$key";
		
		//加密
		$mysign=md5($signStr);
		if($mysign==$sign){
			
				if($opstate==0){
					//支付成功，请处理订单
					echo "opstate=0";
					
					
				}else if($opstate==-1){
					echo "请求参数无效";
					
				}else if($opstate==-2){
					echo "签名错误";
					
				}
				exit;
			
		}else{
			echo "交易数据被串改";
		}

?>
<html>
<head>
<title>酷卡支付</title>
</head>
<body>
    
</body>
</html>