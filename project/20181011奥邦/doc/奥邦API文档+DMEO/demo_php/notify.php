<?php 


date_default_timezone_set("Asia/Shanghai");

//--------------------------------------------1、基础参数配置------------------------------------------------

const TRX_KEY = '7udoz4wv67fsht3ryg1brzesuj2grjue'; //商户后台支付key
const SECRET_KEY = '109b6f64bed6e252437e9de376ed2e7f'; //商户后台支付密钥



if ($_GET) {
    $str = $_GET;
    $trx_key = $str['trx_key']; //商户支付KEY
    $ord_amount = number_format($str['ord_amount'], 2, '.', '');//保留两位小数
    $request_id = $str['request_id'];
    $trx_status = $str['trx_status'];
    $product_type = $str['product_type'];
    $request_time = $str['request_time'];
    $goods_name = $str['goods_name'];
    $trx_time = $str['trx_time'];
    $pay_request_id = $str['pay_request_id'];
    $remark = $str['remark']; //不参与签名



    $params = array(
        'trx_key' => $trx_key,
        'ord_amount' => $ord_amount,
        'request_id' => $request_id,//商户支付请求订单号
        'trx_status' => $trx_status, //订单状态
        'product_type' => $product_type, //3.2	产品类型 (更多请查看API文档3.2产品类型)
        'request_time' => $request_time,//下单时间 格式yyyyMMddHHmmss  如：20180114210635
        'goods_name' => $goods_name, //商品名称
        'trx_time' => $trx_time, //交易时间
        'pay_request_id' => $pay_request_id, //平台交易流水号
   
    );

    $sign = get_sign($params,SECRET_KEY); //取得签名



    if ($sign === $str['sign']) {
        //签名验证成功
        if($trx_status == 2){ //支付成功
            //回调处理
            echo "SUCCESS";
            exit;
        }
    } else {
        //签名验证失败
        exit;
    }
}


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