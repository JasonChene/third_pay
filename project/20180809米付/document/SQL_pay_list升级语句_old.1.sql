
INSERT INTO `7k111data1_db`.`pay_list` (pay_name,pay_wyUrl,pay_wxUrl,pay_zfbUrl,is_wx,is_wy,is_zfb,wy_postUrl,wx_postUrl,zfb_postUrl,wy_returnUrl,wx_returnUrl,zfb_returnUrl,wy_synUrl,wx_synUrl,zfb_synUrl,is_direct,is_qq,qq_postUrl,qq_returnUrl,qq_synUrl)
 VALUES ('米付', '/pay/pay.php', '/pay/wxpay.php', '/pay/zfbpay.php', '1', '1', '1', '/pay/mifu/post.php', '/pay/mifu/wxpost.php', '/pay/mifu/zfbpost.php', '/pay/mifu/return_url.php', '/pay/mifu/return_url.php', '/pay/mifu/return_url.php', '/pay/mifu/notify_url.php', '/pay/mifu/notify_url.php', '/pay/mifu/notify_url.php', '1', '1', '/pay/mifu/qqpost.php', '/pay/mifu/return_url.php', '/pay/mifu/notify_url.php');

INSERT INTO `7k111data1_db`.`pay_set` (`pay_name`, `mer_id`, `mer_key`, `mer_account`, `pay_domain`, `pay_type`, `is_wy`, `is_wx`, `is_zfb`, `show_name`, `is_qq`)
 VALUES ('米付', '2018180', 'mZrLCqeGgOuHgJpWOwxwbmIbjNvkYVFZ', NULL, 'http://pay7.5566205.com', '米付', '1', '1', '1', '米付', '1');

INSERT INTO `testdata1_db`.`pay_list` (pay_name,pay_wyUrl,pay_wxUrl,pay_zfbUrl,is_wx,is_wy,is_zfb,wy_postUrl,wx_postUrl,zfb_postUrl,wy_returnUrl,wx_returnUrl,zfb_returnUrl,wy_synUrl,wx_synUrl,zfb_synUrl,is_direct,is_qq,qq_postUrl,qq_returnUrl,qq_synUrl)
 VALUES ('米付', '/pay/pay.php', '/pay/wxpay.php', '/pay/zfbpay.php', '1', '1', '1', '/pay/mifu/post.php', '/pay/mifu/wxpost.php', '/pay/mifu/zfbpost.php', '/pay/mifu/return_url.php', '/pay/mifu/return_url.php', '/pay/mifu/return_url.php', '/pay/mifu/notify_url.php', '/pay/mifu/notify_url.php', '/pay/mifu/notify_url.php', '1', '1', '/pay/mifu/qqpost.php', '/pay/mifu/return_url.php', '/pay/mifu/notify_url.php');

INSERT INTO `testdata1_db`.`pay_set` (`pay_name`, `mer_id`, `mer_key`, `mer_account`, `pay_domain`, `pay_type`, `is_wy`, `is_wx`, `is_zfb`, `show_name`, `is_qq`)
 VALUES ('米付', '2018180', 'mZrLCqeGgOuHgJpWOwxwbmIbjNvkYVFZ', NULL, 'http://paytest.7k111.com', '米付', '1', '1', '1', '米付', '1');
