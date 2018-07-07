<?php
	$_POST = json_decode(file_get_contents("php://input",'r'), true);
	$merchant_no=$_POST['merchant_no'];							//商户号
	$orders=$_POST['orders'];									//代付結果通知
	$key = "8359aaa5-ad06-11e7-9f73-71f4466";					//商户接口秘钥
	$sign=$_POST['sign'];										//md5加密串
	//MD5签名
	$post=array(
		"merchant_no"=>$merchant_no,
		"orders"=>$orders
	);
    $src = json_encode($post);
    $src .= $key;

	if($sign==md5($src))
	{
		foreach($orders as $k=>$v)
		{
			if ($v['result'] == 'S') {
				/*
				S：代付成功处理
				*/
				echo "S";
			}
			if ($v['result'] == 'F') {
				/*
				F：代付失败处理
				*/
				echo "F";
			}
			if ($v['result'] == 'H') {
				/*
				H：代付处理中处理
				*/
				echo "H";
			}
		}
		
	}else
	{
		/*
		MD5签名错误处理
		*/
		echo "MD5签名错误";
	}
?>