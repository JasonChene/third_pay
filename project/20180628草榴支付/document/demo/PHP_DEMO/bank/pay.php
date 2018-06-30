<?php 
            $apiurl = "http://pay.caoliupay.com/Pay.html";
            $appid = $_POST[txtappid];
            $key = $_POST[txtKey];
            $ordernumber =$_POST[txtordernumber];
            $paytype =$_POST[txtpaytype];
            $attach = $_POST[txtattach];
            $paymoney =$_POST[txtpaymoney];
            $callbackurl = $_POST[txtcallbackurl];
            $signSource = sprintf("appid=%s&paytype=%s&paymoney=%s&ordernumber=%s&callbackurl=%s%s", $appid, $paytype, $paymoney, $ordernumber, $callbackurl, $key);
            $sign = md5($signSource);
            $postUrl = $apiurl. "?paytype=".$paytype;
			$postUrl.="&appid=".$appid;
            $postUrl.="&paymoney=".$paymoney;
            $postUrl.="&ordernumber=".$ordernumber;
            $postUrl.="&callbackurl=".$callbackurl;
            $postUrl.="&attach=".$attach;
            $postUrl.="&sign=".$sign;
			header ("location:$postUrl");
?>