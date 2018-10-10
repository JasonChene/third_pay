<?php

		$apiurl = "www.aferp.cn/Pay_Alipay_pay.html";
		
		$_POST = !empty($_POST)?$_POST:$_GET;
		
        $ordernumber = time(); //商户订单号
		
        $_POST["pay_applydate"] = date("Y-m-d H:i:s");
        $_POST["pay_memberid"] = "10001";
        $_POST["pay_hrefbackurl"] = "notify.php";//服务器异步通知页面路径
        $_POST["pay_callbackurl"] = "page.php";//页面跳转同步通知页面路径
		$apikey = "";//商户后台apikey
		
        $signSource = sprintf("pay_memberid=%s&pay_orderid=%s&pay_applydate=%s&pay_notifyurl=%s&pay_callbackurl=%s&pay_amount=%s%s", $_POST['pay_memberid'], $ordernumber, $_POST['pay_applydate'], $_POST['pay_hrefbackurl'],  $_POST['pay_callbackurl'], $_POST['pay_amount'],$apikey); 
		$sign = strtoupper(md5($signSource));  //字符串加密处理
		
        $postUrl = $apiurl."?pay_memberid=".$_POST['pay_memberid'];
        $postUrl.="&pay_amount=".$_POST['pay_amount'];
        $postUrl.="&pay_orderid=".$ordernumber;
		$postUrl.="&pay_passcode=1063";
        $postUrl.="&pay_applydate=".$_POST['pay_applydate'];
        $postUrl.="&pay_notifyurl=".$_POST["pay_hrefbackurl"];//服务器异步通知页面路径
        $postUrl.="&pay_callbackurl=".$_POST["pay_callbackurl"];//页面跳转同步通知页面路径
        
        $postUrl.="&pay_md5sign=".$sign;
		
		if(!empty($_POST)){
			header ("location:$postUrl");
		}
		
}

?>

