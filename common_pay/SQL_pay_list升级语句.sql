INSERT INTO pay_list
    (pay_name,pay_wyUrl,pay_wxUrl,pay_zfbUrl,is_wx,is_wy,is_zfb,
    wy_postUrl,wx_postUrl,zfb_postUrl,
    wy_returnUrl,wx_returnUrl,zfb_returnUrl,
    wy_synUrl,wx_synUrl,zfb_synUrl,is_direct,is_qq,qq_postUrl,qq_returnUrl,qq_synUrl)
VALUES
    ('新衫德', '/pay/pay.php', '/pay/wxpay.php', '/pay/zfbpay.php', '1', '1', '1',
        '/pay/xinshande/post.php', '/pay/xinshande/wxpost.php', '/pay/xinshande/zfbpost.php',
        '/pay/xinshande/return_url.php', '/pay/xinshande/return_url.php', '/pay/xinshande/return_url.php',
        '/pay/xinshande/notify_url.php', '/pay/xinshande/notify_url.php', '/pay/xinshande/notify_url.php',
        '1' , '1', '/pay/xinshande/qqpost.php', '/pay/xinshande/return_url.php', '/pay/xinshande/notify_url.php');
