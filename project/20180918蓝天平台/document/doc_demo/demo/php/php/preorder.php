<?php
$preorderApi = "http://weixin.9s88.cn/platform/pay/unifiedorder";//下单接口，以文档为准
$mch_id = "aaa111"; //商户号
$key = "affbffbc0f458139cfade013dc4b6b0b";//商户密钥
$body="vip";//商品
$total_fee="100";//价格
$spbill_create_ip="192.168.1.12";//客户端ip
$notify_url="http://www.baidu.com/";//通知地址
$redirect_url="http://www.baidu.com/";//跳转前端地址
$trade_type="WX";//支付方式
$out_trade_no=time();

$signstr="body=".$body."&mch_id=".$mch_id."&notify_url=".$notify_url."&out_trade_no=".$out_trade_no."&redirect_url=".$redirect_url."&spbill_create_ip=".$spbill_create_ip."&total_fee=".$total_fee."&trade_type=".$trade_type."&key=".$key;

$sign = md5($signstr);

$params["sign"] = $sign;//签名
$params["mch_id"] = $mch_id;
$params["body"] = $body;
$params["total_fee"] = $total_fee;
$params["spbill_create_ip"] = $spbill_create_ip;
$params["notify_url"] = $notify_url;
$params["redirect_url"] = $redirect_url;
$params["trade_type"] = $trade_type;
$params["out_trade_no"] = $out_trade_no;

$ret =$preorderApi . "?" . http_build_query($params);

header("Location: ".$ret);     
exit; 
