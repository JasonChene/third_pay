
INSERT INTO `7k111data1_db`.`pay_list` (pay_name,pay_wyUrl,pay_wxUrl,pay_zfbUrl,is_wx,is_wy,is_zfb,wy_postUrl,wx_postUrl,zfb_postUrl,wy_returnUrl,wx_returnUrl,zfb_returnUrl,wy_synUrl,wx_synUrl,zfb_synUrl,is_direct,is_qq,qq_postUrl,qq_returnUrl,qq_synUrl)
 VALUES ('青蛙金服', '/pay/pay.php', '/pay/wxpay.php', '/pay/zfbpay.php', '1', '1', '1', '/pay/qingwajinfu/post.php', '/pay/qingwajinfu/wxpost.php', '/pay/qingwajinfu/zfbpost.php', '/pay/qingwajinfu/return_url.php', '/pay/qingwajinfu/return_url.php', '/pay/qingwajinfu/return_url.php', '/pay/qingwajinfu/notify_url.php', '/pay/qingwajinfu/notify_url.php', '/pay/qingwajinfu/notify_url.php', '1', '1', '/pay/qingwajinfu/qqpost.php', '/pay/qingwajinfu/return_url.php', '/pay/qingwajinfu/notify_url.php');

INSERT INTO `7k111data1_db`.`pay_set` (`pay_name`, `mer_id`, `mer_key`, `mer_account`, `pay_domain`, `pay_type`, `is_wy`, `is_wx`, `is_zfb`, `show_name`, `is_qq`)
 VALUES ('青蛙金服', '11144', 'b3387d0ee47865ed3c939ccb0d5c2c62', NULL, 'http://pay7.5566205.com', '青蛙金服', '1', '1', '1', '青蛙金服', '1');

INSERT INTO `testdata1_db`.`pay_list` (pay_name,pay_wyUrl,pay_wxUrl,pay_zfbUrl,is_wx,is_wy,is_zfb,wy_postUrl,wx_postUrl,zfb_postUrl,wy_returnUrl,wx_returnUrl,zfb_returnUrl,wy_synUrl,wx_synUrl,zfb_synUrl,is_direct,is_qq,qq_postUrl,qq_returnUrl,qq_synUrl)
 VALUES ('青蛙金服', '/pay/pay.php', '/pay/wxpay.php', '/pay/zfbpay.php', '1', '1', '1', '/pay/qingwajinfu/post.php', '/pay/qingwajinfu/wxpost.php', '/pay/qingwajinfu/zfbpost.php', '/pay/qingwajinfu/return_url.php', '/pay/qingwajinfu/return_url.php', '/pay/qingwajinfu/return_url.php', '/pay/qingwajinfu/notify_url.php', '/pay/qingwajinfu/notify_url.php', '/pay/qingwajinfu/notify_url.php', '1', '1', '/pay/qingwajinfu/qqpost.php', '/pay/qingwajinfu/return_url.php', '/pay/qingwajinfu/notify_url.php');

INSERT INTO `testdata1_db`.`pay_set` (`pay_name`, `mer_id`, `mer_key`, `mer_account`, `pay_domain`, `pay_type`, `is_wy`, `is_wx`, `is_zfb`, `show_name`, `is_qq`)
 VALUES ('青蛙金服', '11144', 'b3387d0ee47865ed3c939ccb0d5c2c62', NULL, 'http://paytest.7k111.com', '青蛙金服', '1', '1', '1', '青蛙金服', '1');
