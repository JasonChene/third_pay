<?php
header('Content-type:text/html;charset=utf-8');
// 返回字段
   $return = array(
            "returncode" => $_REQUEST["returncode"],
             "transaction_id" =>  $_REQUEST["transaction_id"], // 流水号
            "memberid" => $_REQUEST["memberid"], // 商户ID
            "orderid" =>  $_REQUEST["orderid"], // 订单号
            "amount" =>  $_REQUEST["amount"], // 交易金额
            "datetime" =>  $_REQUEST["datetime"], // 交易时间
        );
        $key = "t4ig5acnpx4fet4zapshjacjd9o4bhbi";
        ksort($return);
        reset($return);
        //验签
        $str = "";
        foreach ($return as $k => $v) {
            $str = $str . $k . "=" . $v . "&";
        }
        $sign = strtoupper(md5($str . "key=" . $key));
        if ($sign == $_REQUEST["sign"]) {
            if ($_REQUEST["returncode"] == "00") {
                   $str = "交易成功！订单号：".$_REQUEST["orderid"];
                   exit($str);
            }
        }
?>