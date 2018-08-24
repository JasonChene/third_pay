
INSERT INTO `7k111data1_db`.`pay_list` (pay_name,pay_wyUrl,pay_wxUrl,pay_zfbUrl,is_wx,is_wy,is_zfb,wy_postUrl,wx_postUrl,zfb_postUrl,wy_returnUrl,wx_returnUrl,zfb_returnUrl,wy_synUrl,wx_synUrl,zfb_synUrl,is_direct,is_qq,qq_postUrl,qq_returnUrl,qq_synUrl)
 VALUES ('盛银', '/pay/pay.php', '/pay/wxpay.php', '/pay/zfbpay.php', '1', '1', '1', '/pay/shengyin/post.php', '/pay/shengyin/wxpost.php', '/pay/shengyin/zfbpost.php', '/pay/shengyin/return_url.php', '/pay/shengyin/return_url.php', '/pay/shengyin/return_url.php', '/pay/shengyin/notify_url.php', '/pay/shengyin/notify_url.php', '/pay/shengyin/notify_url.php', '1', '1', '/pay/shengyin/qqpost.php', '/pay/shengyin/return_url.php', '/pay/shengyin/notify_url.php');

INSERT INTO `7k111data1_db`.`pay_set` (`pay_name`, `mer_id`, `mer_key`, `mer_account`, `pay_domain`, `pay_type`, `is_wy`, `is_wx`, `is_zfb`, `show_name`, `is_qq`)
 VALUES ('盛银', '34393032', 'd57a2b05447e305a0f67ff0e5627cc86', NULL, 'http://pay7.5566205.com', '盛银', '1', '1', '1', '盛银', '1');

INSERT INTO `testdata1_db`.`pay_list` (pay_name,pay_wyUrl,pay_wxUrl,pay_zfbUrl,is_wx,is_wy,is_zfb,wy_postUrl,wx_postUrl,zfb_postUrl,wy_returnUrl,wx_returnUrl,zfb_returnUrl,wy_synUrl,wx_synUrl,zfb_synUrl,is_direct,is_qq,qq_postUrl,qq_returnUrl,qq_synUrl)
 VALUES ('盛银', '/pay/pay.php', '/pay/wxpay.php', '/pay/zfbpay.php', '1', '1', '1', '/pay/shengyin/post.php', '/pay/shengyin/wxpost.php', '/pay/shengyin/zfbpost.php', '/pay/shengyin/return_url.php', '/pay/shengyin/return_url.php', '/pay/shengyin/return_url.php', '/pay/shengyin/notify_url.php', '/pay/shengyin/notify_url.php', '/pay/shengyin/notify_url.php', '1', '1', '/pay/shengyin/qqpost.php', '/pay/shengyin/return_url.php', '/pay/shengyin/notify_url.php');

INSERT INTO `testdata1_db`.`pay_set` (`pay_name`, `mer_id`, `mer_key`, `mer_account`, `pay_domain`, `pay_type`, `is_wy`, `is_wx`, `is_zfb`, `show_name`, `is_qq`)
 VALUES ('盛银', '34393032', 'd57a2b05447e305a0f67ff0e5627cc86', NULL, 'http://paytest.7k111.com', '盛银', '1', '1', '1', '盛银', '1');
