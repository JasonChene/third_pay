INSERT INTO pay_list(pay_name,pay_wyUrl,pay_wxUrl,pay_zfbUrl,is_wx,is_wy,is_zfb,
wy_postUrl,wx_postUrl,zfb_postUrl,
wy_returnUrl,wx_returnUrl,zfb_returnUrl,
wy_synUrl,wx_synUrl,zfb_synUrl,is_direct,is_qq,qq_postUrl,qq_returnUrl,qq_synUrl) VALUES
 ('讯通宝', '/pay/pay.php', '/pay/wxpay.php', '/pay/zfbpay.php', '1', '1', '1',
 '/pay/xuntongbao/post.php', '/pay/xuntongbao/wxpost.php', '/pay/xuntongbao/zfbpost.php',
 '/pay/xuntongbao/return_url.php', '/pay/xuntongbao/return_url.php', '/pay/xuntongbao/return_url.php',
 '/pay/xuntongbao/notify_url.php', '/pay/xuntongbao/notify_url.php', '/pay/xuntongbao/notify_url.php',
 '1' ,'1','/pay/xuntongbao/qqpost.php','/pay/xuntongbao/return_url.php','/pay/xuntongbao/notify_url.php');
