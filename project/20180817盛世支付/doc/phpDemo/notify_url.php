<?php
//支付结果通知，示例代码段，处理完成之后，请按照协议返回
	header("Content-Type: text/html; charset=utf-8");
	$returnContent=file_get_contents("php://input");
	if($returnContent==null)
	{
		echo 'null request';
		exit;
	}
	
	$obj = json_decode($returnContent);
	$field055 = explode('|',$obj->{'field055'});
	$rescode=$field055[1];
	$payno=$field055[0];
	$money=$field055[3]/100;
	$typ=$field055[4];
	$paytime=$field055[2];
	if($rescode !='00')
	{
		//失败逻辑--处理开始
		//...
		//失败逻辑--处理结束
		$array = array("field039"=> "99");   
		echo json_encode($array);
		exit
	}
	else
	{
		//成功--业务逻辑开始
		//...
		//成功--业务逻辑结束
		$array = array("field039"=> "00");   
		echo json_encode($array);
		exit
	}
?>