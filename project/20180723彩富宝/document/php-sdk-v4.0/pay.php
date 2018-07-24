<?php
include("config.php");
include("util.php");
date_default_timezone_set('Asia/Shanghai');  //设置时区
class Pay
{
    /**
     * 发起支付
     * @param $config
     * @param $params
     */
    public function dopay($config, $params)
    {
        //请求数据
        $data = [];
        $data['version'] = '4.0';                                       //版本号
        $data['app_id'] = $config['app_id'];                            //商户APP_ID
        $data['pay_type'] = 20;                                         //充值渠道
        $data['nonce_str'] = Util::createStr();                         //随机字符串
        $data['sign_type'] = 'MD5';                                     //签名类型
        $data['body'] = 'xinyunbao';                                    //商品描述
        $data['out_trade_no'] = $params['orderid'];                     //商户订单号
        $data['fee_type'] = 'CNY';                                      //标价币种
        $data['total_fee'] = (int)sprintf('%.2f',$params['money'])*100;                               //支付金额  单位：分
        $data['return_url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/return.php';                    //跳转地址
        $data['notify_url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/notify.php';                    //回调地址
        $data['system_time'] = date('YmdHis', time());         //交易时间
        $data['sign'] = Util::createSign($data, $config['app_secret']);         //签名
        $res = Util::postJson($config['url'], $data);
        return $res;
    }
}



$model = new Pay;
$params['money'] = 100;       //支付金额
$params['orderid'] = date("YmdHis",time()).mt_rand(10000,99999); //商户订单号
$res = $model->dopay($config, $params);
$res = json_decode($res, true);
var_dump($res);
