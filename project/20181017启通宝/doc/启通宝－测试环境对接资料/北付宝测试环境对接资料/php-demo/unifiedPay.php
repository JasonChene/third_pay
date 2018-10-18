<?php
/*
 *  PHP 聚合支付 DEMO
 *  RSA密钥格式 PKCS1 1024bit字节证书
 *
 *  常见问题:
 *  1.签名校验错误
 *     a. 公私钥密钥对不匹配，商户未把商户公钥配置到平台中
 *     b. businessContext业务参数未按ASCII大小从小到大排序
 *     c. businessContext转出来的JSON字符串带多余空格
 *     d. php的json_encode转出来的JSON字符串中文会自动UNICODE编码，需要强制不使用UNICODE编码
 *  2.加密校验错误
 *     a. 公私钥密钥对不匹配，商户使用的是不是系统提供的公钥
 *     b. 加密方法错误
 *  3.接收回调,密文解出来是空字符串
 *     a. 使用的不是对应的私钥解密
 *  4.本地验签失败
 *     a.使用的不是平台公钥验签
 *     b.返回的验签businessContext业务参数规则同步第1点b.c.d
 *     c.sign需要经过base64_decode再进行验签
 */
$merchant_private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDDQakcqdInHJ1Q2u0Pi2qBYU0WxTWG24Kga3uQ78QNIiXtXy8BdNid4exr9hXQW34byNc5nor/HRUn31hh8PNvVd8y4B6WJDfYKY+Bq5+rSInhi0O1o0Ht2myjYi9rV9/oVdzfOIdF3MqgKEvrNxhsHyuJ9dteHQoGXtWSRnEIDQIDAQABAoGADlxB583FmwLLvyqazM3gI2vYk5gle6mhTdMZ32sC7ERarb6WYnEJjXMURExxBkX0XG7FBYPXjTPCXpBam7lw7dpgR9BhFm09+FLqPlirr64HQlAwQwDyFmQJuGPq5ASzl7e+fIM8qAqWEH6HuEtFSljmebHo2+6OwLxzNcivGTECQQDrq2AQf1moG4Fs1aNNNvETNL5b8doCIjEaZV26V0bNdHKemxjPbhuxENx6bqnIAEaDl5OrajXOgI3WPz8+M2cTAkEA1BnJ2mizVP/Jn+jArwgLfCJYHR/5u589zkGsLly2ugdf3nFZi6pOHWE460AbPzWXXRMUpoJEl+bF6DEUk/wYXwJAb+y7OfqRhRJTHHI2FVTTl5CEG7y4Ei1U7rlXk0kh+i+kxAja9qDPi/97BraJ8c+XraWOX2mY1lMdibQOACd/ewJBAJNCrHEmDIzRY23RLibYUREIz2C5WKy5rTHNSvyNhpi2kgtha6iav82KOPis8735OXR30PiirXlB0tqZaQ4uE8UCQFUlhs1Av7nZAlPOWxOwUxPyYqebWKoi0FFhvYqrd49BHth8bcA1dFJXu0dAIHYnWbxKDBcoERvt61si4ALG+V4=
-----END RSA PRIVATE KEY-----';
/*商户自己生成的私钥*/

$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDQakcqdInHJ1Q2u0Pi2qBYU0WxTWG24Kga3uQ78QNIiXtXy8BdNid4exr9hXQW34byNc5nor/HRUn31hh8PNvVd8y4B6WJDfYKY+Bq5+rSInhi0O1o0Ht2myjYi9rV9/oVdzfOIdF3MqgKEvrNxhsHyuJ9dteHQoGXtWSRnEIDQIDAQAB
-----END PUBLIC KEY-----';
/*商户自己生成的公钥*/

$bfb_pay_key='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCOAoslcPOFmqk/Okv5sT3z+TsnwjCXtev4OPTM9oLQpr7DwHNYlXIxGkI0rf0RWW6zKMXvrNCYXBjanUYvi0ukM0ujLJiZ+qMutRzxkckqN1ZXSRsjPoCG7S46M1Ew52TKYYkPm/53gqe+gQzdIEDAg8cuxIbSiuKGr2em/jnRfQIDAQAB
-----END PUBLIC KEY-----';
/*由BFB提供的公钥*/

$server_url = 'http://39.108.134.13:10017';

    $businessHead = array(
        'charset' =>'utf-8',
        'version'=>'1.0',
		'method'=>'UNIFIED_PAYMENT',
        'memberNumber'=>'BFB02001093001095',
        'requestTime'=>date("Ymdhms"),
        'signType'=>'RSA',
        'sign'=>'',
     );

    $businessContext = array(
		"defrayalType"=> "ALI_NATIVE",
        "memberOrderNumber"=> date("Ymdhms"),
        "tradeCheckCycle"=> "T1",
        "orderTime"=> "20180101000000",
        "currenciesType"=> "CNY",
        "tradeAmount"=> "1",
        "commodityBody"=> "商品信息",
        "commodityDetail"=> "商品详情",
        "commodityRemark"=> "商品备注",
        "notifyUrl"=> "http://test.shb03.net/bfb/PHPdemoNotify.php",
        "returnUrl"=> "returnUrl",
        "terminalIP"=> "127.0.0.0",
        "terminalId"=> "terminalId",
        "userId"=> "userId",
        "attach"=> "author xiaoshu",
        "remark"=> "author xiaoshu"
        );

    echo("[步骤1：组装businessHead头参数和businessContext业务参数]\n");
    print_r($businessHead);
    print_r($businessContext);

    /** 对业务数据继续排序 **/
    ksort($businessContext);

    echo("[步骤2：对businessContext业务参数按ASCII码从小到大进行排序]\n");
    print_r($businessContext);

    /**业务数据转化为JSON格式**/
    $json_businessContext = json_encode($businessContext, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    echo("[步骤3：将排序后的businessContext业务参数转成JSON字符串]\n");
    echo($json_businessContext."\n");

    $mer_private_key = openssl_pkey_get_private($merchant_private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
    //---------------------------------------------------------------------------------------------
    openssl_sign($json_businessContext, $sign, $mer_private_key, OPENSSL_ALGO_MD5);//MD5方式 使用商户私钥进行签名

    $sign = base64_encode($sign);//最终的签名

    echo("[步骤5：对生成的业务参数JSON进行md5形式的RSA签名]\n");
    echo("sign = ".$sign."\n");


    $businessHead['sign'] = $sign;//将签名加入businessHead中
    $arr_order['businessHead'] = $businessHead;
    $arr_order['businessContext'] = $businessContext;

    $json_order = json_encode($arr_order, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    echo("[步骤6：生成的sign拼接到businessHead中生成]\n");
    echo($json_order."\n");

    $bfb_public_key = openssl_pkey_get_public($bfb_pay_key);
    $cryptos = rsa_encrypt($json_order, $bfb_public_key);

    $context = array(
        'context' => $cryptos,
    );

    echo("[步骤7：加密组装成context的JSON字符串]\n");
    $json_context = json_encode($context);
    echo($json_context."\n");


    list($return_code, $json_return_content) = http_post_data($server_url."/api/payment/unifiedPay", $json_context);
    echo("[收到返回参数]\n");
    echo($json_return_content."\n");

    $context_arr = json_decode($json_return_content, true);
    if ($context_arr['success']){
        $context_str = $context_arr['context'];
        $context_decrypt = rsa_decrypt($context_str, $mer_private_key);
        echo("[步骤8：解密context得到业务返回]\n");
        echo($context_decrypt."\n");

        $return_context = json_decode($context_decrypt, true);
        $return_sign = $return_context['businessHead']['sign'];
        $return_businessContext = $return_context['businessContext'];

        ksort($return_businessContext);
        $return_json_businessContext = json_encode($return_businessContext, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $isVerify = openssl_verify($return_json_businessContext, base64_decode($return_sign), $bfb_public_key, OPENSSL_ALGO_MD5);

        echo("[步骤9：取解密的businessContext按ASCII从小到大进行排序]进行排序并转成JSON字符串，取解密的businessHead中的sign签名，进行验签对比]\n");
        echo("businessContext业务参数:".$return_json_businessContext."\n");
        echo("sign:".$return_sign."\n");
        echo("验签结果:".$isVerify."\n");
    }

    exit;

    function http_post_data($url, $data_string) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json; charset=utf-8",
            "Content-Length: " . strlen($data_string))
        );
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();
        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return array($return_code, $return_content);
    }

    /**
     * RSA加密   RSA公钥长字符串加密 单次加密最大长度为(key_size/8)-11 1024bit的证书为117bytes, 这里1024bit的用117bytes
     * @param $encrypted
     * @param rsa_public_key
     * @return string
     */
    function rsa_encrypt($encrypted, $rsa_public_key){
        $crypto = '';
        foreach (str_split($encrypted, 117) as $chunk) {
            openssl_public_encrypt($chunk, $encryptDatas, $rsa_public_key);
            $crypto .= $encryptDatas;
        }
        return base64_encode($crypto);
    }
    /**
     * RSA解密
     * @param $encrypted
     * @param $rsa_private_key
     * @return string
     */
    function rsa_decrypt($encrypted, $rsa_private_key){
        $crypto = '';
        $encrypted = base64_decode($encrypted);
        foreach (str_split($encrypted, 128) as $chunk) {
            openssl_private_decrypt($chunk, $decryptData, $rsa_private_key);
            $crypto .= $decryptData;
        }
        return $crypto;
    }