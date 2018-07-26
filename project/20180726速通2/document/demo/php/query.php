<?php
    require_once 'inc.php';
	function pay(){
        $mchNo = "test";
        $mchKey = "93204576aac1544a7915b6cde7554950";
		$param = array(
            'mch_id'=>'test',               //商户号
            'out_trade_no'=>"121515657607",    //订单号
        );
        
		$param['sign'] = makeSignature($param, $mchKey);
		$url_param = arrayToKeyValueString($param);
        
		//提交
		$backdata = file_get_contents('http://www.sutong88.com/query?'.$url_param);
		echo $backdata;
		$rjo = json_decode($backdata);
		$payUrl = $rjo->data['payUrl'];
	}

	pay();//支付
?>