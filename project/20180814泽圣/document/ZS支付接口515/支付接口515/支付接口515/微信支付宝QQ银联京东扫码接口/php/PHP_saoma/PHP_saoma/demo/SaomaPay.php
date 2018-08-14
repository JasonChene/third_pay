<?php

echo 'sasw21';
$merchantCode = '1000000001';
$outOrderId = 'asdqdwezxfas';
$amount = '10';
$orderCreateTime = '20170107004747';
$noticeUrl = 'http://120.26.233.89/zspay/callback.php';
$isSupportCredit = '1';
$md5Key = '123456ADSEF';
// 参与签名字段
$sign_fields1 = Array(
    "merchantCode",
    "outOrderId",
    "amount",
    "orderCreateTime",
    "noticeUrl",
    "isSupportCredit"
);
$map1 = Array(
    "merchantCode" => $merchantCode,
    "outOrderId" => $outOrderId,
    "amount" => $amount,
    "orderCreateTime" => $orderCreateTime,
    "noticeUrl" => $noticeUrl,
    "isSupportCredit" => $isSupportCredit
);


$sign0 = sign_mac($sign_fields1, $map1, $md5Key);
// 将小写字母转成大写字母
$sign1 = strtoupper($sign0);


// 使用方法
$post_data1 = array(
    'model' => 'QR_CODE',
    'merchantCode' => $merchantCode,
    'outOrderId' => $outOrderId,
    'deviceNo' => '',
    'amount' => $amount,
    'goodsName' => 'c1a330ee',
    'goodsExplain' => 'c1a330ee1',
    'ext' => 'ext',
    'orderCreateTime' => $orderCreateTime,
    'lastPayTime' => '20170209004747',
    'noticeUrl' => $noticeUrl,
    'goodsMark' => 'goodsMark',
    'isSupportCredit' => $isSupportCredit,
    'ip' => '127.0.0.1',
	 'payChannel' => '21',//21微信，30-支付宝
    'sign' => $sign1
);
$res = send_post('', $post_data1);
echo $res;

/*发送数据  */
function send_post($url, $post_data)
{
    $postdata = http_build_query($post_data);
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 15 * 60
        ) // 超时时间（单位:s）

    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}

/* 构建签名原文 */
function sign_src($sign_fields, $map, $md5_key)
{
    // 排序-字段顺序
    sort($sign_fields);
    $sign_src = "";
    foreach ($sign_fields as $field) {
        $sign_src .= $field . "=" . $map[$field] . "&";
    }
    $sign_src .= "KEY=" . $md5_key;
    
    return $sign_src;
}

/**
 * 计算md5签名  返回的是小写的，后面需转大写
 */
function sign_mac($sign_fields, $map, $md5_key)
{
    $sign_src = sign_src($sign_fields, $map, $md5_key);
    return md5($sign_src);
}
?>
