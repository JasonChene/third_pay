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
	//随机字符串
	$nonceStr = $dateEncrypt->nonceStr();
	
	$params = array(

			'tranCode'  		=>  '2101',   								  //交易码
			'agtId'  		 	=>  '10000001',   				  			  //机构号
			'merId'  		 	=>  '1000000100',   				 		  //商户号
			'orderId'  	    	=>  'OF'.time(),   			  		  		  //商户订单号
			'tranDate'  	    =>  date('Ymd'),   			  			  	  //交易日期
			'nonceStr'  	    =>  $nonceStr,   				 	          //随机字符串
			'txnAmt'  	    	=>  '1000',   				 		  		  //代付金额 单位分
			
			'accountNo'  	    =>  '6236680030000000000',   				  //账户 ,到账卡号
			//'certNum'  	    =>  '511602199310263496',   				  //证件号码
			//'bankCode'  	    =>  'CCB',   				 		  		  //银行编码
			'bankName'  	    =>  $dateEncrypt->String2Hex('建设银行'),   	  //银行名称 
			'accountName'  	    =>  $dateEncrypt->String2Hex('张三'),   	  //账户名
			//'bankProv'  	    =>  '1000',   				 		  		  //开户省
			//'bankCity'  	    =>  '1000',   				 		  		  //开户市
			'cnaps'  	    	=>  '105393000021',   				 		  //联行号 
			//'bankBranch'      =>  $this->String2Hex('中国建设银行股份有限公司厦门市分行营业部'),//开户支行
			//'mobile'  	    =>  '15860357063',   				 		  //手机号 
			'accountType'  	    =>  '1',   				 		  		 	  //0 对公 1 对私
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