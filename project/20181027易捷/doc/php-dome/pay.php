<?php

//公用请求报文
//版本号
$version = "V2.1";
//商户号
$merNo = "80061";

//请求数据, 以密文形式传输，加密前为 JSON字符串,不同的支付方式对应不 同的参数。
//固定值: wx_h5(微信H5) ali_h5(支付宝H5) ysf_h5(云闪付H5) qq_h5(QQ钱包H5) union_h5(银联H5)
$payDataJSON['payType'] = "ali_h5";
//交易完成后会通过该地址进行页面跳转,该跳转需上游渠道支持。
$payDataJSON['returnUrl'] = "http://www.demo.com/payreturn.php";
//异步接收交易结果地址,交易完成后会延时把支付结果通过服务器端返回此路径,如果您接受到该通知并处理完成，请返回 SUCCESS 停止通知,否则将会在一段时间后再次通知,重复通知次数为 3 次。
$payDataJSON['notifyUrl'] = "http://www.demo.com/payreturn.php";
//商户订单号只能包含数字、字母、下划线，且订单号首尾不能是下划线。
$payDataJSON['orderNo'] = "N_12345678907671";
$payDataJSON['webSite'] = "www.baidu.com";
//单位（元）。只能为数字，最多保留两位小数
$payDataJSON['orderAmount'] = "1.5";
//商品信息，包括商品名以及简要描述
$payDataJSON['goodsInfo'] = "Iphone_XS_MAX";
//持卡人 IP 地址
$payDataJSON['ip'] = "10.214.2.35";
//持卡人邮箱
$payDataJSON['email'] = "1065088@qq.com";
//传入交易备注,返回数据时会原封不动的返回。
$payDataJSON['remark'] = "www.baidu.com";
//按key值排序
ksort($payDataJSON);

//生成签名原串
$signInfo = "";
foreach ($payDataJSON as $key => $val) {
    if ($signInfo != "") {
        $signInfo = $signInfo . "&";
    }
    $signInfo = $signInfo . $key . "=" . $val;
}

//实例化签名类
$rsaUtils = new RSAUtils();

//在payDataJSON中加入签名
//echo $signInfo;
$payDataJSON['signInfo'] = $rsaUtils->md5Sign(str_replace("\/", "/", $signInfo));
echo "签名原串:  " . $signInfo . "\n";
echo "签名密串:  " . $payDataJSON['signInfo'] . "\n";
//使用平台公钥 将$payDataJSON进行 RSA公钥加密 后得到payData
$payData = $rsaUtils->publicEncrypt("/platform_public_key.pem", json_encode($payDataJSON));

echo "请求参数明文:  " . json_encode($payDataJSON) . "\n";
echo "请求参数密文:  " . $payData . "\n";

//组装POST参数
$post_data = array(
    'version' => $version,
    'merNo' => $merNo,
    'payData' => $payData
);

//请求接口
$http_resp_content_encrypt = json_decode(send_post("http://58.82.232.169/gateway/payin/pay", $post_data));
echo "接口相应报文:  " . json_encode($http_resp_content_encrypt) . "\n";

$resp_payData = $http_resp_content_encrypt->payData;

//使用 <<商户私钥>> 解密返回的  payData  报文
$resp_payData = $rsaUtils->privateDecrypt("/mer_private_key.pem", $resp_payData);
echo $resp_payData . "\n";

$resp_payData_array;
foreach (json_decode($resp_payData) as $key => $val) {
    if ($key == "signInfo") {
        continue;
    }
    $resp_payData_array[$key] = $val;
}

ksort($resp_payData_array);

$signInfo_str = "";
foreach ($resp_payData_array as $key => $val) {
    if ($signInfo_str != "") {
        $signInfo_str = $signInfo_str . "&";
    }
    $signInfo_str = $signInfo_str . $key . "=" . $val;
}
echo "验签原串:  " . $signInfo_str . "\n";

$resp_signInfo = json_decode($resp_payData)->signInfo;
echo "返回的签名:  " . $resp_signInfo . "\n";

//使用 <<平台公钥>> 进行验签
echo "验签结果:  ";
echo $rsaUtils->signIsValid("/platform_public_key.pem", $signInfo_str, $resp_signInfo);


/**
 * 发送post请求
 * @param string $url 请求地址
 * @param array $post_data post键值对数据
 * @return string
 */
function send_post($url, $post_data)
{
    $postdata = http_build_query($post_data);
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}


class RSAUtils
{

    /**
     * RSA公钥解密(私钥加密的内容通过公钥可以解密出来)
     * @param string $public_key 公钥
     * @param string $data 私钥加密后的字符串
     * @return string $decrypted 返回解密后的字符串
     * @author mosishu
     */
    public function publicDecrypt($public_key_url, $data)
    {
        $public_key = file_get_contents(dirname(__FILE__) . $public_key_url);
        $decrypted = '';
        $pu_key = openssl_pkey_get_public($public_key);//这个函数可用来判断公钥是否是可用的
        $plainData = str_split(base64_decode($data), 128);//生成密钥位数 1024 bit key
        foreach ($plainData as $chunk) {
            $str = '';
            $decryptionOk = openssl_public_decrypt($chunk, $str, $pu_key);//公钥解密
            if ($decryptionOk === false) {
                return false;
            }
            $decrypted .= $str;
        }
        return $decrypted;
    }

    /**
     * RSA私钥加密
     * @param string $private_key 私钥
     * @param string $data 要加密的字符串
     * @return string $encrypted 返回加密后的字符串
     * @author mosishu
     */
    public function privateEncrypt($private_key_url, $data)
    {
        $private_key = file_get_contents(dirname(__FILE__) . $private_key_url);
        $encrypted = '';
        $pi_key = openssl_pkey_get_private($private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        //最大允许加密长度为117，得分段加密
        $plainData = str_split($data, 100);//生成密钥位数 1024 bit key
        foreach ($plainData as $chunk) {
            $partialEncrypted = '';
            $encryptionOk = openssl_private_encrypt($chunk, $partialEncrypted, $pi_key);//私钥加密
            if ($encryptionOk === false) {
                return false;
            }
            $encrypted .= $partialEncrypted;
        }
        $encrypted = base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
        return $encrypted;
    }

    /**
     * 公钥钥加密
     * @param string $data
     * @return bool|string
     */
    public
    function publicEncrypt($public_key_url, $data)
    {
        $public_key = file_get_contents(dirname(__FILE__) . $public_key_url);
        $encrypted = '';
        $pu_key = openssl_pkey_get_public($public_key);
        //PHP加密长度限制,需进行分割加密后再重新拼装
        $plainData = str_split($data, 100);
        foreach ($plainData as $chunk) {
            $partialEncrypted = '';
            $encryptionOk = openssl_public_encrypt($chunk, $partialEncrypted, $pu_key);//公钥加密
            if ($encryptionOk === false) {
                return false;
            }
            $encrypted .= $partialEncrypted;
        }
        $encrypted = base64_encode($encrypted);
        return $encrypted;
    }

    /**
     * 私钥解密
     * @param $data
     * @return bool|string
     */
    public function privateDecrypt($private_key_url, $data)
    {
        $private_key = file_get_contents(dirname(__FILE__) . $private_key_url);
        $decrypted = '';
        $pi_key = openssl_pkey_get_private($private_key);
        $plainData = str_split(base64_decode($data), 128);
        foreach ($plainData as $chunk) {
            $str = '';
            $decryptionOk = openssl_private_decrypt($chunk, $str, $pi_key);//私钥解密
            if ($decryptionOk === false) {
                return false;
            }
            $decrypted .= $str;
        }
        return $decrypted;
    }

    /**
     * 私钥生成数字签名
     * @param $data 待签数据
     * @return String 返回签名
     */
    public
    function md5Sign($data = '')
    {
        if (empty($data)) {
            return False;
        }
        $private_key = file_get_contents(dirname(__FILE__) . '/mer_private_key.pem');
        if (empty($private_key)) {
            echo ");Private Key error!";
            return False;
        }

        $pkeyid = openssl_get_privatekey($private_key);
        if (empty($pkeyid)) {
            echo "private key resource identifier False!";
            return False;
        }

        $verify = openssl_sign($data, $signature, $pkeyid, OPENSSL_ALGO_MD5);
        openssl_free_key($pkeyid);
        return base64_encode($signature);
    }

    /**
     * 利用公钥和数字签名验证合法性
     * @param $public_key_url 公钥地址
     * @param $data 待验证数据
     * @param $signature 数字签名
     * @return -1:error验证错误 1:correct验证成功 0:incorrect验证失败
     */
    public
    function signIsValid($public_key_url, $data = '', $signature = '')
    {
        if (empty($data) || empty($signature)) {
            return False;
        }

        $public_key = file_get_contents(dirname(__FILE__) . $public_key_url);
        if (empty($public_key)) {
            echo "Public Key error!";
            return False;
        }

        $pkeyid = openssl_get_publickey($public_key);
        if (empty($pkeyid)) {
            echo "public key resource identifier False!";
            return False;
        }

        $ret = openssl_verify($data, base64_decode($signature), $pkeyid, OPENSSL_ALGO_MD5);
        switch ($ret) {
            case -1:
                echo "error";
                break;
            default:
                echo $ret == 1 ? "correct" : "incorrect";//0:incorrect
                break;
        }
        return $ret;
    }


}