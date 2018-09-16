<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
//第二种JSON方式调用
$json_url = 'https://api.561581.com/api/query';


$merchantId ='6001024'; //测试商户ID  6001024
$secretKey='9AF6D9E349707FC20139B433D314AD6C'; //测试商户秘钥    请登录小财神商家后台获取最新秘钥
$merchantOrderNo= 'oid1534346233';//商户自定的订单号，该订单号将后在后台展示。
//$merchantOrderNo= 'oid1534347113';//商户自定的订单号，该订单号将后在后台展示。
$timestamp      = time()*1000;//时间截

$sign           = md5($merchantOrderNo.'&'.$merchantId.'&'.$timestamp.'&'.$secretKey);
$param = array(
        'merchantId'        => $merchantId,                         //必填。您的商户唯一标识，注册后在设置里获得
        'timestamp'         => $timestamp,                          //必填。精确到毫秒
        'merchantOrderNo'   => $merchantOrderNo,                    //必填。商户自定的订单号，该订单号将后在后台展示。
        'sign'              => $sign,                                //必填。把参数，连Token一起，按指定的顺序。做md5-32位加密，取字符串小写。得到key。
    );
$res =  request_post($json_url,$param);
echo $res;





/**
 * 模拟post进行url请求
 * @param string $url
 * @param string $param
 */
function request_post($url = '', $param = '') {
    if (empty($url) || empty($param)) {
        return false;
    }

    $postUrl = $url;
    $curlPost = $param;
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);

    return $data;
}





?>