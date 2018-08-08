
INSERT INTO `7k111data1_db`.`pay_list` (pay_name,pay_wyUrl,pay_wxUrl,pay_zfbUrl,is_wx,is_wy,is_zfb,wy_postUrl,wx_postUrl,zfb_postUrl,wy_returnUrl,wx_returnUrl,zfb_returnUrl,wy_synUrl,wx_synUrl,zfb_synUrl,is_direct,is_qq,qq_postUrl,qq_returnUrl,qq_synUrl)
 VALUES ('龙宝', '/pay/pay.php', '/pay/wxpay.php', '/pay/zfbpay.php', '1', '1', '1', '/pay/longbao/post.php', '/pay/longbao/wxpost.php', '/pay/longbao/zfbpost.php', '/pay/longbao/return_url.php', '/pay/longbao/return_url.php', '/pay/longbao/return_url.php', '/pay/longbao/notify_url.php', '/pay/longbao/notify_url.php', '/pay/longbao/notify_url.php', '1', '1', '/pay/longbao/qqpost.php', '/pay/longbao/return_url.php', '/pay/longbao/notify_url.php');

INSERT INTO `7k111data1_db`.`pay_set` (`pay_name`, `mer_id`, `mer_key`, `mer_account`, `pay_domain`, `pay_type`, `is_wy`, `is_wx`, `is_zfb`, `show_name`, `is_qq`)
 VALUES ('龙宝', '1570', '2Zm8JJQcnvJ5OOBt4FXqYOZ9hmdL1vRA', NULL, 'http://pay7.5566205.com', '龙宝', '1', '1', '1', '龙宝', '1');

INSERT INTO `testdata1_db`.`pay_list` (pay_name,pay_wyUrl,pay_wxUrl,pay_zfbUrl,is_wx,is_wy,is_zfb,wy_postUrl,wx_postUrl,zfb_postUrl,wy_returnUrl,wx_returnUrl,zfb_returnUrl,wy_synUrl,wx_synUrl,zfb_synUrl,is_direct,is_qq,qq_postUrl,qq_returnUrl,qq_synUrl)
 VALUES ('龙宝', '/pay/pay.php', '/pay/wxpay.php', '/pay/zfbpay.php', '1', '1', '1', '/pay/longbao/post.php', '/pay/longbao/wxpost.php', '/pay/longbao/zfbpost.php', '/pay/longbao/return_url.php', '/pay/longbao/return_url.php', '/pay/longbao/return_url.php', '/pay/longbao/notify_url.php', '/pay/longbao/notify_url.php', '/pay/longbao/notify_url.php', '1', '1', '/pay/longbao/qqpost.php', '/pay/longbao/return_url.php', '/pay/longbao/notify_url.php');

INSERT INTO `testdata1_db`.`pay_set` (`pay_name`, `mer_id`, `mer_key`, `mer_account`, `pay_domain`, `pay_type`, `is_wy`, `is_wx`, `is_zfb`, `show_name`, `is_qq`)
 VALUES ('龙宝', '1570', '2Zm8JJQcnvJ5OOBt4FXqYOZ9hmdL1vRA', NULL, 'http://paytest.7k111.com', '龙宝', '1', '1', '1', '龙宝', '1');
