<?php
/**
 * ---------------------通知异步回调接收页-------------------------------
 * 
 * 此页就是您之前传给支付页的notifyurl页的请求接口
 * 支付成功，我们会根据您之前传入的网址，回调此页URL，post回参数
 * 
 * --------------------------------------------------------------
 */

    $p_id = $_POST["payid"];
    $orderid = $_POST["orderid"];
    $price = $_POST["paymoney"];
    $realprice = $_POST["realpay"];
    $orderuid = $_POST["orderuid"];
    $key = $_POST["key"];

    //校验传入的参数是否格式正确，略

    $token = "此处填写Token";
    //payid=10000000480012&orderid=10000000480010&orderuid=10000000480011&paymoney=0.01&realpay=0.01&token=2f44c6b906208208c4122c83125ccd7b
    $temps = md5("payid=".$payid ."&orderid=". $orderid
                ."&orderuid=". $orderuid ."&paymoney=". $paymoney
                ."&realpay=". $realpay."&token=". $token);

    if ($temps != $key){
        return jsonError("key值不匹配");
    }else{
        //校验key成功，是自己人。执行自己的业务逻辑：加余额，订单付款成功，装备购买成功等等。
        
    }

    //返回错误
    function jsonError($message = '',$url=null) 
    {
        $return['msg'] = $message;
        $return['data'] = '';
        $return['code'] = -1;
        $return['url'] = $url;
        return json_encode($return);
    }

    //返回正确
    function jsonSuccess($message = '',$data = '',$url=null) 
    {
        $return['msg']  = $message;
        $return['data'] = $data;
        $return['code'] = 1;
        $return['url'] = $url;
        return json_encode($return);
    }
    
  

?>