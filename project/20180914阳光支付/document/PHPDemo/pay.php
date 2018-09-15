<?php
/**
 * ---------------------参数生成页-------------------------------

 * 
 * 在你的服务器上生成新订单，并把计算好的订单信息传给您的前端网页。
 * 注意：
 * 1.key一定要在服务端计算，不要在网页中计算。
 * 2.token只能存放在服务端，不可以以任何形式存放在网页代码中，也不可以通过url参数方式传入网页。
 * --------------------------------------------------------------
 */

    //从网页传入paymoney:支付金额， paytype:支付渠道：1-支付宝；2-微信支付
    $paymoney = $_POST["paymoney"];
    $paytype = $_POST["paytype"];
    
    $orderuid = "username";       //此处传入付款用户的用户名，方便在后台对账，建议加上。

    //校验传入的表单，确保价格为正常价格（整数，2位小数）

    //此处就在您服务器生成新订单，并把创建的订单号传入到下面的orderid中。
    $uid = "";//支付平台分配给你的唯一标识
    $token = "";//支付平台分配给你的唯一秘钥
    
    $ordername = "测试订单";
    $orderid = "1234567890";    //订单id。
    $returnurl = 'http://www.demo.com/payreturn.php';
    $notifyurl = 'http://www.demo.com/paynotify.php';
    $orderinfo = '订单备注';
    
    
	

    $key = md5("uid=".$uid ."&orderid=" . $orderid ."&ordername=". $ordername 
        ."&paymoney=". $paymoney ."&orderuid=" . $orderuid . "&paytype=" . $paytype 
        ."&notifyurl=". $notifyurl ."&returnurl=" .$returnurl ."&orderinfo=". $orderinfo."&token=". $token);        
    //注意事项：1.参数的排列顺序；2.参数不能少传；3.即使没有对应参数值，参数名称必须带进去key的计算。

    $returndata['uid'] = $uid;
    $returndata['orderid'] = $orderid;
    $returndata['ordername'] = $ordername;
    $returndata['paymoney'] = $paymoney;
    $returndata['orderuid'] = $orderuid;
    $returndata['paytype'] =$paytype;
    $returndata['notifyurl'] = $notifyurl;
    $returndata['returnurl'] = $returnurl;
    $returndata['orderinfo'] = $orderinfo;
    $returndata['key'] = $key;
	
    echo jsonSuccess("OK",$returndata,"");


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