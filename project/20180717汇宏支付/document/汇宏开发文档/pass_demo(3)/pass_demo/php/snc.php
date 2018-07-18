<?php
	$data = file_get_contents("php://input"); 
	$resdata = urldecode(urldecode($data));
	$resdata = simplexml_load_string(substr($resdata,4), 'SimpleXMLElement', LIBXML_NOCDATA);
	$val = json_decode(json_encode($resdata),true); 
		if($val['code'] == '0000'){
			$signPars = "";
			ksort($val);	
        	foreach($val as $k => $v) {
            	if("" != $v && "sign" != $k) {
                	$signPars .= $k . "=" . $v . "&";
            	}
        	}
			
			$key = '12345678901234567890123456789012';
		
        	$signPars .= "key=" . $key;
		
			$signPars = mb_convert_encoding($signPars, "GBK", "UTF-8");
        	$Ressign = md5($signPars);
			if ($Ressign != $val['sign']) {
				echo '签名验证失败';
          	} 
			else{
				//业务处理	
				
				echo '1';		
			}	
		}
		else {	  
		 	
			echo $val['code'];
		 	
      	}
	
?>