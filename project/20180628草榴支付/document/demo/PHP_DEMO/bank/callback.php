<?php 
            $appid = "10000001";//商户ID
            $Key = "a006e912ceb3eb4d9682d9aa6b47b291";//商户KEY
            $orderstatus = $_GET["orderstatus"];
            $ordernumber = $_GET["ordernumber"];
            $paymoney = $_GET["paymoney"];
            $sign = $_GET["sign"];
            $attach = $_GET["attach"];
            $signSource = sprintf("appid=%s&ordernumber=%s&orderstatus=%s&paymoney=%s%s", $appid, $ordernumber, $orderstatus, $paymoney, $Key); 
            if ($sign == md5($signSource))//签名正确
            {
                //此处作逻辑处理
            }
			echo('success');exit;

?>