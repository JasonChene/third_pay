<?php
/**
 * ---------------------通知异步回调接收页-------------------------------
 *
 * 此页就是您之前传给支付页的notify_url页的网址
 * 支付成功，回调此页URL，post回参数
 *
 * --------------------------------------------------------------
 */
include('config.php');
include('util.php');
$post = $_POST;
$return_code = $post['return_code'] ? $post['return_code'] : '';
$result_code = $post['result_code'] ? $post['result_code'] : '';
$trade_state = $post['trade_state'] ? $post['trade_state'] : '';
$sign = $post['sign'] ? $post['sign'] : '';
if($trade_state == 'SUCCESS'){          //交易状态成功
    $app_secret = $config['app_secret'];
    //签名数据
    $data = [];
    $data['app_id'] = $post['app_id'];
    $data['nonce_str'] = $post['nonce_str'];
    $data['out_trade_no'] = $post['out_trade_no'];
    $data['sign_type'] = 'MD5';
    $data['total_fee'] = $post['total_fee'];
    $data['version'] = '4.0';
    $signStr = Util::createSign($data, $app_secret);
    if($sign == $signStr) {
        //订单处理----------------

        echo 'SUCCESS';
    }
}
echo 'FAIL';

