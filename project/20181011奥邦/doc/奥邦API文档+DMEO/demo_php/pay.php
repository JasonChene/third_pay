<?php
date_default_timezone_set("Asia/Shanghai");

//--------------------------------------------1、基础参数配置------------------------------------------------

const TRX_KEY = '7udoz4wv67fsht3ryg1brzesuj2grjue'; //商户后台支付key
const SECRET_KEY = '109b6f64bed6e252437e9de376ed2e7f'; //商户后台支付密钥
const API_URL = 'https://www.aobangapi.com/pay'; //支付API请求地址


//--------------------------------------------end基础参数配置------------------------------------------------

//-----------------------------------------以下为网关支付请求参数--------------------------------------------------

//实例数据
/*$params = array(
    'trx_key' => TRX_KEY,
    'ord_amount' => '10.00',
    'request_id' => date('YmdHis', time()).mt_rand(1,100000),//商户支付请求订单号
    'request_ip' => get_client_ip(), //用户下单IP地址
    'product_type' => '10103', //3.2	产品类型 (更多请查看API文档3.2产品类型) 如：微信扫码 10103
    'request_time' => '20180114210635',//下单时间 格式yyyyMMddHHmmss  如：20180114210635
    'goods_name' => 'hahaha', //支付产品名称
    'bank_code' => 'bank_code', //银行编码
    'account_type' => 'PRIVATE_DEBIT_ACCOUNT', //交易卡类型
    'return_url' => 'http://www.baidu.com',// 页面通知地址
    'callback_url' => 'http://ab.com/notify.php', //后台异步通知地址
    //'remark' => '1111',  //备注  可选项
);

//-----------------------------------------以下为无卡支付请求参数--------------------------------------------------
//实例数据
$params = array(
    'trx_key' => TRX_KEY,
    'ord_amount' => '10.00',
    'request_id' => date('YmdHis', time()).mt_rand(1,100000),//商户支付请求订单号
    'request_ip' => get_client_ip(), //用户下单IP地址
    'product_type' => '10103', //3.2	产品类型 (更多请查看API文档3.2产品类型) 如：微信扫码 10103
    'request_time' => '20180114210635',//下单时间 格式yyyyMMddHHmmss  如：20180114210635
    'goods_name' => 'hahaha', //商品名称
    //'remark' => '1111',  //备注  可选项
    'return_url' => 'http://www.baidu.com',// 页面通知地址
    'callback_url' => 'http://ab.com/notify.php', //后台异步通知地址

);*/




//--------------------------------------------end基础参数配置------------------------------------------------
if($_POST){
    $ord_amount = number_format($_POST['ord_amount'], 2, '.', '');//保留两位小数
    $request_id = 'TEST'.date('YmdHis', time()).mt_rand(1,100000);
    $request_ip = get_client_ip();
    $product_type = $_POST['product_type'];
    $bank_code = $_POST['bank_code']?$_POST['bank_code']:'';
    $request_time = date('YmdHis',time());
}


//-----------------------------------------以下为网关支付请求参数--------------------------------------------------
if($bank_code){
    $params = array(
        'trx_key' => TRX_KEY,
        'ord_amount' =>$ord_amount,
        'request_id' => date('YmdHis', time()).mt_rand(1,100000),//商户支付请求订单号
        'request_ip' => get_client_ip(), //用户下单IP地址
        'product_type' => $product_type, //3.2	产品类型 (更多请查看API文档3.2产品类型) 如：微信扫码 10103
        'request_time' => $request_time,//下单时间 格式yyyyMMddHHmmss  如：20180114210635
        'goods_name' => 'hahaha', //支付产品名称
        'bank_code' => $bank_code, //银行编码
        'return_url' => 'http://ab.com/notify.php',// 页面通知地址
        'callback_url' => 'http://ab.com/notify.php', //后台异步通知地址
        //'remark' => '1111',  //备注  可选项
    );
}else{
    //-----------------------------------------以下为无卡支付请求参数--------------------------------------------------
    $params = array(
        'trx_key' => TRX_KEY,
        'ord_amount' =>$ord_amount,
        'request_id' => date('YmdHis', time()).mt_rand(1,100000),//商户支付请求订单号
        'request_ip' => get_client_ip(), //用户下单IP地址
        'product_type' => $product_type, //3.2	产品类型 (更多请查看API文档3.2产品类型) 如：微信扫码 10103
        'request_time' => $request_time,//下单时间 格式yyyyMMddHHmmss  如：20180114210635
        'goods_name' => 'hahaha', //支付产品名称
        'return_url' => 'http://xxx.com/notify.php',// 页面通知地址
        'callback_url' => 'http://xxx.com/notify.php', //后台异步通知地址
        //'remark' => '1111',  //备注  可选项

    );
}

$sign = get_sign($params,SECRET_KEY); //取得签名
$sign_array =array('sign'=>$sign);
$params = array_merge($params,$sign_array);
echo  _buildForm($params,API_URL); // 以表单隐藏域的形式，通过post方式提到API接口地址


/***
 * get sign
 * $params 签名所需要的参数
 * $secret_key  商户后台支付密钥
 */

function get_sign($params,$secret_key){
    ksort($params);
    $paramStr = "";
    //拼接字符串参数
    while (list ($key, $val) = each($params)) {
        $paramStr.=$key . "=" . $val . "&";
    }
//去掉最后一个&字符
    $paramStr = substr($paramStr, 0, -1);
    $preSignStr = $paramStr."&secret_key=".$secret_key;  //此字串要来生成签名
    $sign = strtoupper(md5($preSignStr));
    return $sign;
}


/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
function get_client_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
        $ip     =   $_SERVER['HTTP_X_REAL_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 构造表单
 */
function _buildForm($params, $gateway, $method = 'post', $charset = 'utf-8') {
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");
    header("Content-type:text/html;charset={$charset}");
    $sHtml = "<form id='paysubmit' name='paysubmit' action='{$gateway}' method='{$method}'>";

    foreach ($params as $k => $v) {
        $sHtml.= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
    }

    $sHtml = $sHtml . "</form>Loading......";

    $sHtml = $sHtml . "<script>window.onload= function(){document.getElementById('paysubmit').submit();};</script>";
    return $sHtml;
}




