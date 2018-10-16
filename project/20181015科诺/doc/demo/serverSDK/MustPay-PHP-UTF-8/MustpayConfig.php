<?php
/* *
 *功能：基础配置
 *详细：设置帐户有关信息及返回路径
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 *该代码仅供学习和研究MustPay接口使用，只是提供一个参考。
 */

global $MustpayConfig;

//MustPay平台公钥
$MustpayConfig['plate_public_key'] = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDO7CQpYHhEonv1g9YjRVGJDaCOu0bXogD7pBLQu2dDvJ8TGROCEw6ArIWgAWEEE1uEShPBa4MpCP4ZMjT5RMj45o0pb0Z8s4k6CpS9D1LFK9msNpsN8PyaRDQC86R6jxAVQMWgfIZ9cxfZR8Ple3GJGjwBfeRnzh75rE1DHCBOcwIDAQAB';

//商户私钥
$MustpayConfig['mer_private_key'] = 'MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKCB7DD1Vbb4zVh5gUIQv0JbPiiTQHcZKVPzfbXxS76IDB2y6pTOq2PbXiURrag7a0FVLsdlJX+zIX8d93KNeLn+Fa8Cb0oJeZQzciZYDdFEui3HtwizY3DoP2mnrk4faEzMXUGlMUrBV7WBMopxwspup0x+rtgn5h20lOUYg0ybAgMBAAECgYAVwgb2jAtWhluvxqjS/9otcJj4fx2aB3smujcsVs1hwqeBzyMlkO6C1tXoSIE18PgVHyr8NKXkra+4v6MvkCXxOZvY4wVNL8RjaMksjtZNbBRaddIe2psqklFDH8do2BoxfvBdnkWulz9A//k2U08N4c2kJ5AUCe6nE5UjNFJcgQJBAM8wBJcDV1sEGUShaKWuTIt/JdPPJdYbw/SPCSDQRBvjjaQqu2nr8B6ONDLXZqey1yS7LJF1hFdzDC4kCXCYNpECQQDGUoSH1CPeP3IGFPAui20kJNPKgm9Zecf+/VTa78WPtOF5DwHVSHD4X+qt2Hrp1dLCgGmLltFHNV+b/SslR55rAkBXCthSzT+M6ErpT1pUiMZ1sIQm2RcPPXj0rIbsNzL1+IKQHre/xzSI0btSRLZG69aBAvW1Yoan6piKZe9lUz1RAkA7IuPt9K31WYnQknHED0MuIeUdX6OAVLX0LOoelpyca11IUddEF+PHzCIYUJLmIyJDaTMPspsY1qt5whYZea+dAkAJFamFbLtnag7ol1Q8LO4r44nf3OnpOV61T/pOyxLAj9lKtx/vizyihM5/2OcOK0mpt9YgbDmDx0MVSUbvn8RT';

//测试商户apps_id
$MustpayConfig['apps_id'] = '5d0006abd0414412b6d994cbd7dcc85d';

//测试商户mer_id
$MustpayConfig['mer_id'] = '17072512021831085';

//异步回调URL 此地址必须外网可访问
$MustpayConfig['notify_url'] = 'http://60.205.211.235/notify_url.php';

//同步回调URL 此地址必须外网可访问
$MustpayConfig['return_url'] = 'http://60.205.211.235/return_url.php';

// 签名方式
$MustpayConfig['sign_type'] = 'RSA';

// 字符编码格式 目前支持utf-8
$MustpayConfig['input_charset'] = 'utf-8';

//下单地址
$MustpayConfig['add_order_url'] = 'https://service.chinaxiangqiu.com/service/order/saveOrder';

//订单查询地址
$MustpayConfig['query_order_url'] = 'https://service.chinaxiangqiu.com/service/order/queryOrder';

//ca证书路径地址，用于curl中ssl校验
//请保证cacert.pem文件在当前文件夹目录中
$MustpayConfig['cacert']    = getcwd().'\\cacert.pem';

//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

?>