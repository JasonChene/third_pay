<?php
	$pay_memberid = "商户ID";   //商户ID
	$pay_orderid = date("YmdHis");    //订单号
	$pay_amount = "0.2";    //交易金额
	$pay_applydate = date("Y-m-d H:i:s");  //订单时间
	$pay_bankcode = "ICBC";   //银行编码
	$pay_notifyurl = "商户回调地址";   //服务端返回地址
	$pay_callbackurl = "商户回调地址";  //页面跳转返回地址
	
	$Md5key = "商户密钥";   //密钥
	
	$tjurl = "http://我们的地址/Pay_Index.html";   //提交地址
	
	$requestarray = array(
            "pay_memberid" => $pay_memberid,
            "pay_orderid" => $pay_orderid,
            "pay_amount" => $pay_amount,
            "pay_applydate" => $pay_applydate,
            "pay_bankcode" => $pay_bankcode,
            "pay_notifyurl" => $pay_notifyurl,
            "pay_callbackurl" => $pay_callbackurl
        );
		
	    ksort($requestarray);
        reset($requestarray);
        $md5str = "";
        foreach ($requestarray as $key => $val) {
            $md5str = $md5str . $key . "=>" . $val . "&";
        }
		echo($md5str . "key=" . $Md5key."<br>");
        $sign = strtoupper(md5($md5str . "key=" . $Md5key)); 
		$requestarray["pay_md5sign"] = $sign;
		
        $str = '<form id="Form1" name="Form1" method="post" action="' . $tjurl . '">';
        foreach ($requestarray as $key => $val) {
            $str = $str . '<input type="hidden" name="' . $key . '" value="' . $val . '">';
        }
		$str = $str . '<input type="submit" value="提交">';
        $str = $str . '</form>';
        $str = $str . '<script>';
        //$str = $str . 'document.Form1.submit();';
        $str = $str . '</script>';
        exit($str);
?>