<?php
/**
*该实例仅供参考，具体结合自己的应用做具体调整
*验签字段以文档说明为准。
*验签调用encrypt.php类中sign方法
*抛送数据需要encrypt.php类中postJSON方法
*
*/

    //扫码支付接口-下单
	require_once("./encrypt.php");
	$dateEncrypt = new encrypt();

    //MD5密钥
	$key = 'OYbxS2UwTTrQiuoIWCcSfDJKdIgEourZ';
	//接口地址
	$url = 'http://118.31.73.155:8098/webwt/pay/gateway.do';
	//异步回调地址
	$notify = 'https://www.baidu.com/';
	//随机字符串
	$nonceStr = $dateEncrypt->nonceStr();

	$params = array(

			'tranCode'  		=>  '1101',   								  //交易码
			'agtId'  		 	=>  '10000001',   				  			  //机构号
			'merId'  		 	=>  '1000000100',   				 		  //商户号
			'orderAmt'  	    =>  '1000',   				 		  		  //订单总金额 单位分
			'orderId'  	    	=>  'OF'.time(),   			  		  		  //商户订单号
			'goodsName'  	    =>  $dateEncrypt->String2Hex('化妆品'),   	  //商品简单描述
			'notifyUrl'  	    =>  $notify,   				 	              //异步回调地址
			'nonceStr'  	    =>  $nonceStr,   				 	          //随机字符串
			'stlType'  	    	=>  'T0',   				 	         	  //T0 结算至已入账账户 ；T1 结算至未结算账户
			'payChannel'  	    =>  'WXPAY',   				 	         	  //WXPAY ：微信支付；ALIPAY ：支付宝；QQPAY ：qq钱包
	);

	$params = $dateEncrypt->argSort($params);
	$sign = strtoupper($dateEncrypt->md5Sign($dateEncrypt->createLinkstring($params), $key));
	$sign = $dateEncrypt->sign($sign);

	$parm = array(
			'REQ_HEAD' => array('sign'=>$sign),
			'REQ_BODY' => $params,
	);
	print_r($sign);
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
