<?php
include_once("config.php");
file_put_contents('./2.txt',json_encode($_POST));
//商户订单号
$out_trade_no = $_POST['out_trade_no'];
//平台交易号
$dd = $_POST['dd'];
//交易金额
$total_fee = $_POST['total_fee'];

//签名验证
$sign_md5 = md5_sign($_POST,$config['key']);

//验证签名
if($sign_md5 == $_POST['sign']){ 

        echo "success"; //这里只能输出success，不能删除，也不能输出其他内容

        //这里可以对该订单进行操作
        //修改订单状态为1
        get_dingdan($out_trade_no,1);

} else {  //支付失败

    echo "error"; //不能删除
}
?>