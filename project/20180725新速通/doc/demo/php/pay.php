<?php
    require_once 'inc.php';
	function pay(){
        $mchNo = "test";
        $mchKey = "93204576aac1544a7915b6cde7554950";
		$param = array(
            'version'=>'V1.0',              //版本号
            'mch_id'=>$mchNo,               //商户号
            'out_trade_no'=>"12".time(),    //订单号
            'attach'=>"attach",             //商品标题
            'body'=>'body',                 //商品内容
            'amount'=>'0.10',               //金额  (限额标准请查看限额)
            'mch_create_ip'=>'8.8.8.8',     //终端用户IP
            'notify_url'=>('http://localhost/demo/notify.php'),   //异步通知地址
            'return_url'=>('http://localhost'),   //同步跳转地址
            'trade_type'=>'zfbwap'
        );
        
		$param['sign'] = makeSignature($param, $mchKey);
		$url_param = arrayToKeyValueString($param);
        
		//建议使用POST提交
		$backdata = file_get_contents('http://www.sutong88.com/api?'.$url_param);
		echo $backdata;
		$rjo = json_decode($backdata);
		$payUrl = $rjo->data['payUrl'];
	}

    function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
        }

	pay();//支付
?>