<?php
/**➢	接口签名
签名处理机制： 所有数据元采用 key=value 的形式按照名称排序(即按key的字典顺序排序)，
以&作为连接符，最后追加&key=MD5KEY(密钥由平台分配)，最后拼接成待签名串(如果value为空则该key=value不参与签名)；
对待签名串使用md5算法做摘要，将签名后的摘要放入报文字段(sign)中，请特别注意签名前原串用的是key=value键值对。
发送的报文请使用JSON，报文中包含其他字段外，最后一个字段为经过签名后的sign字段。
MD5KEY:每个商户都会分配一个MD5KEY，用户签名，请勿泄露，且不要在报文里传输。
*/


// ↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

$pay_config = array();

// 商户ID号
$pay_config['merId'] = '100000010000016';

// MD5KEY:每个商户都会分配一个MD5KEY，用户签名，请勿泄露，且不要在报文里传输
$pay_config['mercKey'] = 'c53b874709cdfd1d4b260c674cb6a381';

// 平台环境, dev为开发环境, 否则为生产环境
//$pay_config['env'] = 'dev';

// 版本号
$pay_config['version'] = '1.0.0';

// 调试模式, 写入日志
$pay_config['debug'] = true;

