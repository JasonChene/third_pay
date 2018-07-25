<?php
    require_once 'inc.php';

    $mchNo = "test";
    $mchKey = "93204576aac1544a7915b6cde7554950";
    $out_trade_no=$_POST['out_trade_no'];
    $sys_order_no=$_POST['sys_order_no'];
    $trade_type=$_POST['trade_type'];
    $pay_time=$_POST['pay_time'];
    $status=$_POST['status'];
    $amount=$_POST['amount'];
    $mch_id=$_POST['mch_id'];
    $sign=$_POST['sign'];

    $param = array(
        'out_trade_no'=>$out_trade_no,  //商户订单号
        'sys_order_no'=>$sys_order_no,  //平台订单号
        'trade_type'=>$trade_type,      //支付方式
        'pay_time'=>$pay_time,          //支付时间
        'status'=>$status,              //状态 true 成功 false 失败
        'amount'=>$amount,              //实际支付金额
        'mch_id'=>$mch_id,              //商户号
    );

    $mysign = makeSignature($param, $mchKey);

    if(strtolower($sign)==strtolower($mysign)){
        if($status=='true'){
            echo 'success';
        } else {
            echo 'fail';
        }
    } else {
        echo 'signerr';
    }
?>
