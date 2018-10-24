<?php   
    header("Content-type:text/html;charset=utf-8");
    //从网页传入money:支付价格， payWay:支付渠道：103-支付宝；104-微信支付
    $money = $_POST["money"];
    $payWay = $_POST["payWay"];
    
    $orderUid = "username";       //此处传入您网站用户的用户名，方便在paysapi后台查看是谁付的款，强烈建议加上。可忽略。

    //校验传入的表单，确保价格为正常价格（整数，1位小数，2位小数都可以），支付渠道只能是1或者2，orderuid长度不要超过33个中英文字。

    //此处就在您服务器生成新订单，并把创建的订单号传入到下面的orderid中。
    $goodsname = "请叫我商品名称，不要超过33个中英文字";
    $orderId = "1234567890";    //每次有任何参数变化，订单号就变一个吧。
    $shopId = "";//"此处填bearpay的shopId";
    $key = "";//"此处填写bearpay的key";
    $return_url = 'http://www.demo.com/payreturn.php';
    $notify_url = 'http://www.demo.com/paynotify.php';
    
    $token = md5($goodsname.'$'. $payWay .'$'. $notify_url .'$'. $orderId .'$'. $orderUid .'$'. $money .'$'. $return_url .'$'. $key .'$'. $shopId);
    //经常遇到有研发问为啥key值返回错误，大多数原因：1.参数的排列顺序不对；2.上面的参数少传了，但是这里的key值又带进去计算了，导致服务端key算出来和你的不一样。

    $returndata['goodsname'] = $goodsname;
    $returndata['payWay'] = $payWay;
    $returndata['orderId'] = $orderId;
    $returndata['orderUid'] =$orderUid;
    $returndata['money'] = $money;
    $returndata['notify_url'] = $notify_url;
    $returndata['return_url'] = $return_url;
    $returndata['token'] = $token;
    $returndata['shopId'] = $shopId;
    $posturl='http://pay.crossex.cn/bear-pay/pay';                   //bearpay支付api地址
    $PostUrl=$posturl."?";
    foreach ($returndata as $key=>$val){
        $PostUrl=$PostUrl.$key.'='.$val.'&';
    }
    //跳转到指定网站
    if (isset($PostUrl)) { 
        header("Location: $PostUrl"); 
        exit;
     }else{
        echo "<script type='text/javascript'>";
        echo "window.location.href='$PostUrl'";
        echo "</script>";
    };
?>
