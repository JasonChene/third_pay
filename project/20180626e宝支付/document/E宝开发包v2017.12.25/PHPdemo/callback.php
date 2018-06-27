<?php
   error_reporting(0);
   $ReturnArray = array( // 返回字段
            "memberid" => $_POST["memberid"], // 商户ID
            "orderid" =>  $_POST["orderid"], // 订单号
            "amount" =>  $_POST["amount"], // 交易金额
            "datetime" =>  $_POST["datetime"], // 交易时间
            "returncode" => $_POST["returncode"]//响应码
        );
      
   file_put_contents("server.txt",date("Y-m-d H:i:s").'响应码:'.$_REQUEST["returncode"]."-".$_POST["pay_reserved1"].'\r\n', FILE_APPEND);
   
        $Md5key = "c2y1ZEg33yku8ViL7vMzLVsrKQUO1V";//密钥（跳转过程中，请检查密钥是否有泄露！）
   
		ksort($ReturnArray);
        reset($ReturnArray);
        $md5str = "";
        foreach ($ReturnArray as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $Md5key)); 
        if ($sign == $_POST["sign"]) {
			
            if ($_POST["returncode"] == "00000") {
				//数据处理---开始
				 $str = "交易成功！订单号：".$_POST["orderid"];
                 file_put_contents("success.txt",$str."\r\n", FILE_APPEND);
				//数据处理---结束
				
				//业务处理完成务必输出success
				exit('success');
            }
			else
				exit($_POST["returncode"]);
        }
		else
			exit('签名错误');
