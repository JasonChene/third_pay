<?php
/*
*订单退款	
*/
require_once 'config.php';
$isPost = $_SERVER['REQUEST_METHOD']=='GET'?false:true;

//生成退款单号
$refundid = date('Y') . date('m') . date('d') . date('H') . date('i') . date('s') . mt_rand(10000, 99999);
if($isPost){
	//获取表单提交参数
	$params = $_POST;
	//初始化Config类
	$config = new Config;
	$datas = $config->startexecution($params);

	//将返回结果转为数组。
	$return = json_decode($datas,true);
	        if(!is_array($return)){
	            echo '请求失败:'.$datas;
	        }else{
	            //请继续完成您的业务逻辑
	            echo $datas;
	        }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>订单退款</title>
</head>
<body>
	<div>
		<form name='type' method="post" action="refund.php">
			<input type="hidden"  name="pd_FrpId" value="refund"/>
			<input type="hidden"  name="out_refund_no" value="<?php echo $refundid?>"/>
			订单号：<input type="text" name="out_trade_no" value="" style="width:400px; height:30px; padding:4px; line-height:20px;  " />
			<button type="submit" style="width:100px;height:40px;">申请退款</button>
		</form>
	</div>
</body>
</html>