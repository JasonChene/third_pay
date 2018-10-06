<?php
/**
 * Created by PhpStorm.
 * User: 陈远
 * Date: 2018/9/12
 * Time: 14:10
 */
require 'inc.php';
require 'phpqrcode/qrlib.php';
//付款金额
$total_fee = empty($_POST['total_fee']) ? "" : $_POST['total_fee'];
$total_fee = '0.01';
//订单号 自己生成,
$sdorderno = 'Demo_' . time() . mt_rand(1000, 9999);
//支付类型
$paytype = 'alipay';
if (is_numeric($total_fee)) {
    $str = 'version=' . $version . '&customerid=' . $userid . '&total_fee=' . $total_fee . '&sdorderno=' . $sdorderno . '&notifyurl=' . $notifyurl . '&' . $userkey;
    $sign = md5($str);
    $postdata['version'] = $version;
    $postdata['customerid'] = $userid;
    $postdata['total_fee'] = $total_fee;
    $postdata['sdorderno'] = $sdorderno;
    $postdata['notifyurl'] = $notifyurl;
    $postdata['sign'] = $sign;
    $postdata['remark'] = '备注';
    $postdata['paytype'] = $paytype;
    $res = post_curls($payurl . 'apisubmit', $postdata);
    $obj = json_decode($res);
    var_dump($res);
    if ($obj->status == 'Success') {
        //订单创建成功
        //trade_no为商户订单号
        //remark为订单备注
        //qr_code为二维码字符串 需要转换成二维码
        //转成二维码

        echo QRcode::svg($obj->qr_code, false, QR_ECLEVEL_H, 6);


    }else{

    }

} else {


}


