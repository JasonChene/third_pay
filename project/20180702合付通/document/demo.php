
<?php

//第三方光大支付
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set("PRC");
$url = "http://www.jianfengtech.com/cashier/Home";   //接口请求地址	
$arr['mch_id'] = '4';   //商户号
$arr['out_trade_no'] = date("YmdHis") . rand(1000, 9999);   //商户订单号

$arr['callback_url'] = "http://www.biadu.com";   //支付完成跳转地址,前台通知地址
$arr['notify_url'] = "http://www.biadu.com";   //通知地址
$arr['total_fee'] = '10';   //金额
/*
  wx:微信
  al:支付宝
  qq:qq钱包（最小金额10元）
  jd:京东
  wy:网银支付
  kj:快捷支付（最小金额10元）
  yl:银联二维码（最小金额10元）
  说明：其中微信wx接口类型h5为way=wap，其中支付宝al接口类型h5为way=pay
 */
$arr['service'] = 'kj';   //接口类型
/*
  h5：公众号支付或者其他js支付
  pay：扫码或者网关支付
  micropay：被扫支付
  wap：wap支付
  app:app支付
 */

$arr['way'] = 'wap';   //支付方式
$arr['format'] = 'xml';   //返回数据格式
$signature = '57d0551b9ff844f39477d849999577af';   //密钥


$vals = array_values($arr);   //获取数组键值
$strval = implode('', $vals);   //将一个一维数组的值转化为字符串
/* md5(mch_id + out_trade_no + body + callback_url + notify_url + total_fee + service + way+ format + 商户密钥) */
$arr['sign'] = md5($strval . $signature);   //MD5后的校验字段,上面的数组顺序固定。
// $arr['appid'] = '100008';   //应用编号
$arr['mch_create_ip'] = get_onlineip();   //ip地址：wap支付时，wap发起H5终端IP，和微信客户端获得IP需要为同一个IP
$arr['body'] = 'aa';   //商品描述
// $arr['goods_tag'] = 'ABC';   //非网银可以为空，网银编码
 // echo get_onlineip();
//exit($arr['mch_create_ip']);
$urlval = http_build_query($arr);
$url = $url . '?' . $urlval;

header('refresh:0;url='.$url);


//header("Location: $pay_info");
//echo "<script language='javascript'  type='text/javascript'>" . "window.location.href='$pay_info'".  "</script>";  getenv:获取环境变量的值 HTTP_CLIENT_IP:客户端的ip HTTP_X_FORWARDED_FOR:浏览当前页面的用户计算机的网关  REMOTE_ADDR:浏览当前页面的用户计算机的ip地址
function get_onlineip() {
    $onlineip = '';
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    return $onlineip;
}

