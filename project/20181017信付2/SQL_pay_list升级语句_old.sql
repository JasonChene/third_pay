
INSERT INTO `7k111data1_db`.`pay_list` (pay_name,pay_wyUrl,pay_wxUrl,pay_zfbUrl,is_wx,is_wy,is_zfb,wy_postUrl,wx_postUrl,zfb_postUrl,wy_returnUrl,wx_returnUrl,zfb_returnUrl,wy_synUrl,wx_synUrl,zfb_synUrl,is_direct,is_qq,qq_postUrl,qq_returnUrl,qq_synUrl)
 VALUES ('信付2', '/pay/pay.php', '/pay/wxpay.php', '/pay/zfbpay.php', '1', '1', '1', '/pay/xinfu2/post.php', '/pay/xinfu2/wxpost.php', '/pay/xinfu2/zfbpost.php', '/pay/xinfu2/return_url.php', '/pay/xinfu2/return_url.php', '/pay/xinfu2/return_url.php', '/pay/xinfu2/notify_url.php', '/pay/xinfu2/notify_url.php', '/pay/xinfu2/notify_url.php', '1', '1', '/pay/xinfu2/qqpost.php', '/pay/xinfu2/return_url.php', '/pay/xinfu2/notify_url.php');

INSERT INTO `7k111data1_db`.`pay_set` (`pay_name`, `mer_id`, `mer_key`, `mer_account`, `pay_domain`, `pay_type`, `is_wy`, `is_wx`, `is_zfb`, `show_name`, `is_qq`)
 VALUES ('信付2', '100000000000005', 'c33367701511b4f6020ec61ded352059', NULL, 'http://pay7.5566205.com', '信付2', '1', '1', '1', '信付2', '1');

INSERT INTO `testdata1_db`.`pay_list` (pay_name,pay_wyUrl,pay_wxUrl,pay_zfbUrl,is_wx,is_wy,is_zfb,wy_postUrl,wx_postUrl,zfb_postUrl,wy_returnUrl,wx_returnUrl,zfb_returnUrl,wy_synUrl,wx_synUrl,zfb_synUrl,is_direct,is_qq,qq_postUrl,qq_returnUrl,qq_synUrl)
 VALUES ('信付2', '/pay/pay.php', '/pay/wxpay.php', '/pay/zfbpay.php', '1', '1', '1', '/pay/xinfu2/post.php', '/pay/xinfu2/wxpost.php', '/pay/xinfu2/zfbpost.php', '/pay/xinfu2/return_url.php', '/pay/xinfu2/return_url.php', '/pay/xinfu2/return_url.php', '/pay/xinfu2/notify_url.php', '/pay/xinfu2/notify_url.php', '/pay/xinfu2/notify_url.php', '1', '1', '/pay/xinfu2/qqpost.php', '/pay/xinfu2/return_url.php', '/pay/xinfu2/notify_url.php');

INSERT INTO `testdata1_db`.`pay_set` (`pay_name`, `mer_id`, `mer_key`, `mer_account`, `pay_domain`, `pay_type`, `is_wy`, `is_wx`, `is_zfb`, `show_name`, `is_qq`)
 VALUES ('信付2', '100000000000005', 'c33367701511b4f6020ec61ded352059', NULL, 'http://paytest.7k111.com', '信付2', '1', '1', '1', '信付2', '1');
