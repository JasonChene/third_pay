<?php
/**
 *  
 * 出款API-出款商户在支付平台的余额
 *  
 */
$merchantCode = '1000000001';
$md5Key = '123456ADSEF';
$nonceStr='123assdqweZssSad';
// 参与签名字段
$sign_fields1 = Array(
    "merchantCode",
    "nonceStr"
    
);
$map1 = Array(
    "merchantCode" => $merchantCode,
    "nonceStr"=>$nonceStr
   
);


$sign0 = sign_mac($sign_fields1, $map1, $md5Key);
// 将小写字母转成大写字母
$sign1 = strtoupper($sign0);


// 使用方法
$post_data1 = array(
    'nonceStr' => $nonceStr,
    'merchantCode' => $merchantCode,
    'sign' => $sign1
);
//正式地址
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
