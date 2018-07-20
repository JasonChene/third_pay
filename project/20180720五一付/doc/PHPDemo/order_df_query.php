<?php
/**
*该实例仅供参考，具体结合自己的应用做具体调整
*验签字段以文档说明为准。
*验签调用encrypt.php类中sign方法
*抛送数据需要encrypt.php类中postJSON方法
*
*/

	//订单支付查询
	require_once("./encrypt.php");
	$dateEncrypt = new encrypt();
	
    //MD5密钥
	$key = 'OYbxS2UwTTrQiuoIWCcSfDJKdIgEourZ';
	//接口地址
	$url = 'http://118.31.73.155:8098/webwt/pay/gateway.do';
	//随机字符串
	$nonceStr = $dateEncrypt->nonceStr();
	
	$params = array(

			'tranCode'  		=>  '2102',   								  //交易码
			'agtId'  		 	=>  '10000001',   				  			  //机构号
			'merId'  		 	=>  '1000000100',   				 		  //商户号
			'orderId'  	    	=>  'OF'.time(),   			  		  		  //商户订单号
			'tranDate'  	    =>  date('Ymd'),   			  			  	  //交易日期
			'nonceStr'  	    =>  $nonceStr,   				 	          //随机字符串
	);
	 
	$params = $dateEncrypt->argSort($params);
	$sign = strtoupper($dateEncrypt->md5Sign($dateEncrypt->createLinkstring($params), $key));
	$sign = $dateEncrypt->sign($sign);
	
	$parm = array(
			'REQ_HEAD' => array('sign'=>$sign),
			'REQ_BODY' => $params,
	);
	
	$rsHttp = $dateEncrypt->postJSON($url, $parm, 20);
	$rs = json_decode($rsHttp,true);
	
	echo '<br>------------------<br><pre>';
	print_r($parm);
	echo '<br>------------------<br>';
	print_r($rs);
	
	echo '<br>------------------<br>';
	$remark = $rs['REP_BODY']['rspmsg'];
	$remark = $dateEncrypt->Hex2String($remark);
	echo $remark;


?>