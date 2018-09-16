<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
/**
 * ---------------------通知异步回调接收页-------------------------------
 * 
 * 此页就是您之前传给支付页的notifyURL页的网址
 * 支付成功，我们会根据您之前传入的网址，回调此页URL，post回参数
 * 
 * --------------------------------------------------------------
 */

var_dump($_POST);
    $orderNo = $_POST["orderNo"];
    $merchantOrderNo = $_POST["merchantOrderNo"];
    $money = $_POST["money"];
    $payAmount = $_POST["payAmount"];
    $sign = $_POST["sign"];
    //$paytype = $_POST["paytype"];

    //校验传入的参数是否格式正确，略

    $merchantId ='6001024'; //测试商户ID  6001024
    $secretKey='9AF6D9E349707FC20139B433D314AD6C'; //测试商户秘钥    请登录小财神商家后台获取最新秘钥
    

    $key = md5($orderNo.'&'.$merchantOrderNo.'&'.$money.'&'.$payAmount.'&'.$secretKey);

    $text ="回调参数:".$orderNo.'&'.$merchantOrderNo.'&'.$money.'&'.$payAmount.'&'.$sign."\r\n";
    $text .="key=".$key."\r\n";
    $text .="sign=".$sign."\r\n";
    file_put_contents("order/".$merchantOrderNo.'.txt',$text);
    if ($key != $sign){
        return "key值不匹配";
        //
    }else{
        //校验key成功，是自己人。执行自己的业务逻辑：加余额，订单付款成功，装备购买成功等等。
        echo 'ok';  //返回ok代表着接收到了回调，小财神将不再发送回调信息
    }


?>