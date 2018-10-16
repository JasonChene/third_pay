<?php
/* *
*功能：MustPay查询订单接口
*说明：
*以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
*/
if($_POST){
    require_once("MustpayConfig.php");
    require_once("lib/MustpaySubmit.class.php");

    //交易流水号
    $trade_no = $_POST['trade_no'];

    //统一下单时间
    $add_time = $_POST['add_time'];

    //////////////////////////////////////////////////////////////////////////////////
    //构造要请求的参数数组，无需改动
    $parameter = array(
        'apps_id' => $MustpayConfig['apps_id'],
        'trade_no' => $trade_no,
        'mer_id' => $MustpayConfig['mer_id'],
        'add_time' => $add_time
    );

    //建立请求
    $mustpaySubmit = new MustpaySubmit($MustpayConfig);
    $orderInfo = $mustpaySubmit->queryOrder($parameter);

    print_r($orderInfo);exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>MustPay订单查询接口</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        *{margin:0;padding:0;font-family: Arial,microsoft yahei,"微软雅黑";}
        .header{height:26px;line-height:26px;background:#8F8F8F;color:#FFF;}
        .header p{width:1200px;margin:0 auto;text-align:right;font-size:14px;}
        .logo-box{margin:20px auto;width:1200px;}
        .logo-box span{height:36px;line-height:36px;color:#666;font-size:18px;padding-left:20px;}
        .pay-box{margin:30px auto;width:1200px;background:#F8F8F8;border-radius:20px;text-align:center;padding-top:30px;}
        .pay-box .goods-name{padding:30px 0;color:#232323;text-align:center;font-size:22px;}
        .pay-box .goods-price{padding:30px 0;color:#FF6600;text-align:center;font-size:22px;}
        .pay-box .box{padding-top:40px;}
        .pay-box .box label{font-size:15px;color:#232323;width:120px;display:inline-block;text-align:right;}
        .pay-box .box input{width:250px;padding:7px 10px;}
        .pay-box .submit input{width:180px;height:40px;line-height:40px;color:#FFF;margin:80px auto;background:#52e2c6;text-align:center;cursor:pointer;border:0;}
    </style>
</head>
<body>
<div class="header">
    <p>你好，欢迎使用MustPay</p>
</div>

<div class="logo-box">
    <img src="http://pingtai.chinaxiangqiu.com:8098/testpay/images/logo.png" height="36px">
    <span>MustPay测试查询订单Demo</span>
</div>

<div class="pay-box">
    <form action="query.php" method="post">
        <div class="box">
            <label>交易流水号：</label>
            <input type="text" name="trade_no" value="96c6eefaa90041e0bcc0971765d33048">
        </div>
        <div class="box">
            <label>下单时间：</label>
            <input type="text" name="add_time" value="2016-12-25 10:30:51">
        </div>
        <div class="submit">
            <input type="submit" value="确 认">
        </div>
    </form>
</div>
</body>
</html>