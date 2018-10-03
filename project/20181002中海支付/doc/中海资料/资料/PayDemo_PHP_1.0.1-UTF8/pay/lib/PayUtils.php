<?php
/* *
 * 功能：支付
 * 版本：1.0
 * 修改日期：2018-06-09
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */
require_once dirname ( __FILE__ ).'/BaseUtils.php';
class PayUtils {
    //版本
    public $version='1.0';

     //支付网关地址
    public $gateway_url = "https://pay.zhonghaipay.com/pay/create";
    //查询网关地址
    public $query_url = "https://pay.zhonghaipay.com/pay/orderInquire";
    //异步通知
    public $notify_url="/demo/pay/return.php";

    //同步返回
    public $return_url="/demo/pay/return.php";

	//编码格式
	public $charset = "UTF-8";

	//签名方式 
    public $sign_type = "MD5";

     //应用id
    public $appid='7RZLYE7H';

    public $appsecret='f5fde174bab030541d625996ef325139';

    //参与加密的字段
    public $sign_field=array(
        'pay'=>array('version','sign_type','appid','order_type','out_trade_no','total_fee','currency_type','pay_id','goods_name','return_url'),
        'pay_return'=>array('version','sign_type','appid','resp_code','resp_desc','tran_amount','out_trade_no','currency_type','pay_no','pay_id','amount','payment','goods_name'),
        'query'=>array('version','sign_type','appid','out_trade_no'),
        'query_return'=>array('version','sign_type','respCode','respDesc','disAmount','suppInfo','appid','curr_code','pay_no','amount','prepay_id','code_url','time_limit'),
    );
    public function __construct()
	{
        $this->return_url='http://'.$_SERVER['HTTP_HOST'].$this->return_url;
	}
    /**
     * 支付提交
     * @param $tranCode
     * @param $params
     * @return string
     * @throws Exception
     */
    public function paySubmit($data){
        //业务参数
        $business['version']=$this->version;
        $business['sign_type']=$this->sign_type;
        $business['appid']=$this->appid;
        $business['order_type'] = $data['order_type'];
        $business['out_trade_no']=$data['out_trade_no'];
        $business['total_fee']=$data['total_fee'];
        $business['currency_type']=$data['currency_type'];
        $business['pay_id']=$data['pay_id'];
        $business['goods_name']=base64_encode($data['goods_name']);
        $business['return_url']=$this->return_url;      
        $business['sign']=$this->makeRequestSign($business,'pay');
        $result=BaseUtils::buildCurl($business,$this->gateway_url);
        echo "<pre>";
        print_r($result);
        return json_decode($result,true);
    }
    /**
     * 支付提交
     * @param $tranCode
     * @param $params
     * @return string
     * @throws Exception
     */
    public function payQuery($data){
        //业务参数
        $business['version']=$this->version;
        $business['sign_type']=$this->sign_type;
        $business['appid']=$this->appid;     
        $business['out_trade_no']=$data['out_trade_no'];
        $business['sign']=$this->makeRequestSign($business,'query');
        return $result=BaseUtils::buildCurl($business,$this->query_url);
    }
    /**
     * 生成sign字符串
     */
    public function makeRequestSign($data = array(),$type='pay') {
        $raw_arr = array();
        foreach($this->sign_field[$type] as $k=>$v){
            $raw_arr[$v]=$data[$v];  
        }
        $sign=BaseUtils::createMd5Sign($raw_arr,$this->appsecret);
        return $sign;
    }
     /**
     * 验证回调sign字符串-用于与通知中的签名比较验证是否通过
     */
    public function makeNotifySign($data=  array(),$type='pay') {
        $raw_arr = array();
        foreach($this->sign_field[$type] as $k=>$v){
            $raw_arr[$v]=$data[$v]; 
        }
        $status=BaseUtils::verifyMd5Sign($raw_arr,$data['sign'],$this->appsecret);  
        return $status;
    }
    
    
    
    
	

}


