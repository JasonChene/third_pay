<?php
	$gateway_url = 'http://ehuikatong.com/api/pay'; 			//网关地址
	$order = time();											//订单号
	$merchant = '1091';											//商户号
	$key = 'bf6331dab67e734a7887d89a0a805537';					//秘钥
	$total_amount = 1;											//总金额以分为单位
	$notify = 'http://'.$_SERVER ['HTTP_HOST'].'/respond.php';	//回调地址

    //发起支付请求,支付方式选1、2返回的pay_info生成二维码,用户扫码即可支付
	$response = pay($gateway_url, $order, $merchant, $key, $total_amount, $notify);

    var_dump($response);

	function pay($gateway_url, $order, $merchant, $key, $total_amount, $notify) {
		$data['merchant'] = $merchant;
		$data['out_trade_no'] = $order;
		$data['service_type'] = 2;   //1、支付 2、微信
		$data['total_amount'] = $total_amount;
		$data['notify'] = $notify;
		$data['remark'] = 'remark';   //备注信息
		$data['sign'] = sign($data, $key); //签名信息

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($ch, CURLOPT_POST, TRUE); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
        curl_setopt($ch, CURLOPT_URL, $gateway_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);

        $ret = curl_exec($ch);
        curl_close($ch);
//        print_r($data);

        return json_decode($ret, true);

	}

	function sign($params, $key) {
		ksort($params);
		$str = '';
		foreach ($params as $k => $value) {
			$str .= $k.'='.$value.'&';
		}
		$str .= 'key='.$key;
		return strtoupper(md5($str));
	}