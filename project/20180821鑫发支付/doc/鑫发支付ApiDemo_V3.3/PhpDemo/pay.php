<?php 
include('util.php');
class pay{
	private $merchNo;
	private $key;
	private $reqUrl;
	public function __construct(){
		$this->merchNo = 'XF201808160001';
		$this->key = '9416F3C0E62E167DA02DC4D91AB2B21E';
		$this->reqUrl = 'http://127.0.0.1/api/pay';// 测试环境
		$this->query_reqUrl = 'http://127.0.0.1/api/queryPayResult';
	}


	public function pay(){
		$Rsa = new Rsa();
		$data['orderNo'] = date('YmdHis') .rand(10000,99999);
		$data['version'] = 'V3.3.0.0';
		$data['charsetCode'] = 'UTF-8';
		$data['randomNum'] = (string) rand(1000,9999);
		$data['merchNo'] = $this->merchNo;
		$data['payType'] = 'WX';   	//WX:微信支付,ZFB:支付宝支付
		$data['amount'] = '100';	// 单位:分
		$data['goodsName'] = 'iPhone配件';
		$data['notifyUrl'] = 'http://127.0.0.1/pay.php?rec=callback';
		$data['notifyViewUrl'] = 'http://localhost/view';
		$data['sign'] = create_sign($data,$this->key);

		$json = json_encode_ex($data);
		$dataStr = $Rsa->encode_pay($json);
		$param = 'data=' . urlencode($dataStr) . '&merchNo=' . $this->merchNo;

 		$result = wx_post($this->reqUrl,$param);
 		$rows = json_to_array($result,$this->key);

 		if ($rows['stateCode'] == '00'){
 			echo "订单创建成功,以下是订单数据</br>";
 			P($rows); 
  	
 		}else{
 			echo "错误代码：" . $rows['stateCode'] . ' 错误描述:' . $rows['msg'];

 		}
 
	}

	public function query(){
		$Rsa = new Rsa();
		$data['merchNo'] = $this->merchNo;
		$data['payType'] = 'ZFB';// WX:微信支付,ZFB:支付宝支付
		$data['orderNo'] = '20180801102543215VCqeRK';
		$data['amount'] = '10000';
		$data['goodsName'] = 'iPhone配件';
		$data['payDate'] = '2018-08-01';
		$data['sign'] = create_sign($data,$this->key);

		$json = json_encode_ex($data);
		$dataStr = $Rsa->encode_pay($json);
 		$param = 'data=' . urlencode($dataStr) . '&merchNo=' . $this->merchNo;

 		$result = wx_post($this->query_reqUrl,$param);
 		$rows = json_to_array($result,$this->key);
 		if ($rows['stateCode'] == '00'){
 			echo "订单查询成功,以下是订单数据</br>";
 			P($rows); 	#支付状态 00:支付成功 01:支付失败 03:签名错误 04:其他错误 05:未知06:初始 50:网络异常 99:未支付

 		}else{
 			echo "错误代码：" . $rows['stateCode'] . ' 错误描述:' . $rows['msg'];

 		}
	}

	public function callback(){
		$Rsa = new Rsa();
		$data = isset($_POST['data']) ? $_POST['data'] : '';
		if (!empty($data)){
			$data = urldecode($data);
			$data = $Rsa->decode($data);
			$rows = callback_to_array($data,$this->key);
	 		log_write("收到支付回调通知");
	 		log_write(PS($rows));
	 		
		}
		echo "SUCCESS";
	}
}

header("Content-type: text/html; charset=utf-8");
$rec = isset($_GET['rec']) ? $_GET['rec'] : '';
if ($rec == 'pay'){
	$pay = new pay();
	$pay->pay();
}

if ($rec == 'query'){
	$pay = new pay();
	$pay->query();
}

if ($rec == 'callback'){
	$pay = new pay();
	$pay->callback();
}


 
?>