<?php


    //从网页传入price:支付价格， type:支付渠道：1-微信支付；2-支付宝
    $price = $_POST["price"];
    $type = $_POST["type"];
    $order_uid = "user_id";       //此处传入您网站用户的用户id，方便在平台后台查看是谁付的款，强烈建议加上。可忽略。

    //校验传入的表单，确保价格为正常价格（整数，1位小数，2位小数都可以），支付渠道只能是1或者2，orderuid长度不要超过33个中英文字。

    //此处就在您服务器生成新订单，并把创建的订单号传入到下面的orderid中。
    $order_name = "商品名称";
    $order_id = time().rand(1,88);    //每次有任何参数变化，订单号就变一个吧。
    $return_url = '';	//同步返回地址
    $notify_url = '';	//回调地址
	
	
	
	###此处要填写您的账户信息##
	$uid = "";				//"此处填写平台的uid";
    $token = "";			//"此处填写平台的Token";

    $key = md5($notify_url .$order_id .$order_name. $price .$return_url .$token .$type . $uid);
	

    $data['key'] = $key;
    $data['order_name'] = $order_name;
    $data['type'] = $type;
    $data['notify_url'] = $notify_url;
    $data['order_id'] = $order_id;
    $data['order_uid'] =$order_uid;
    $data['price'] = $price;
    $data['return_url'] = $return_url;
    $data['uid'] = $uid;
    echo jsonSuccess("OK",$data,"");


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
        $return['status'] = 1;
        $return['url'] = $url;
        return json_encode($return);
    }

?>