<?php
	$req['mchno'] = 'M201801010001';//商户id
	$req['orderid'] = "201802021628479715";//商户订单号
	$req['istype'] = '1';//支付类型:1（支付宝）、2（微信）
	$req['price'] = '0.01';//商品描述 	
	$req['notify_url'] = "https://pay.weixin.qq.com/wxpay/pay.action";//后台通知url，必须为直接可访问的url，不能携带参数。示例：“https://pay.weixin.qq.com/wxpay/pay.action”
	
	$key = '12345678901234567890123456789012';
	$url = 'http://123.207.108.152/preCreate';
	
	$signPars = "";

    ksort($req);

    foreach($req as $k => $v) {
        if("" != $v && "sign" != $k) {
             $signPars .= $k . "=" . $v . "&";
        }
    }

   $signPars .= "key=".$key;

   $sign = md5($signPars);

   $req['sign'] = $sign;
	
   $xmldata['req'] = URLencode("<xml><mchno>".$req['mchno']."</mchno><orderid>".$req['orderid']."</orderid><istype>".$req['istype']."</istype><price>".$req['price']."</price><notify_url>".$req['notify_url']."</notify_url><sign>".$req['sign']."</sign></xml>");

   try {
   		$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $xmldata);
    	$res = curl_exec($ch);
    	curl_close($ch);
		
	 	$resdata = simplexml_load_string(urldecode($res), 'SimpleXMLElement', LIBXML_NOCDATA); 
   
		$val = json_decode(json_encode($resdata),true); 

		if($val['code'] == '0000' && $val['success'] == true) {
			$signPars = "";
			ksort($val);	
        	foreach($val as $k => $v) {
            	if("" != $v && "sign" != $k) {
                	$signPars .= $k . "=" . $v . "&";
            	}
        	}

        	$signPars .= "key=" . $key;
		
			$signPars = mb_convert_encoding($signPars, "GBK", "UTF-8");
        	$Ressign = md5($signPars);
 								
          	if ($Ressign != $val['sign']) {
				echo '签名验证失败';
          	} 
          	else {
				//业务处理
					
          	}
   		} 
      	else {	  
		 	echo $val['message'].'1'; 	
      }
	
	} catch (Exception $e) {
    	$errorMsg = $e->getMessage();
    	return false;
	}
?>