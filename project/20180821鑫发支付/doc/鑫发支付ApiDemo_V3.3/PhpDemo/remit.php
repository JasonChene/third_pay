<?php 
include('util.php');

class remit{
	private $merchNo;
	private $key;
	private $reqUrl;
	public function __construct(){
		$this->merchNo = 'XF201808160001';
		$this->key = '9416F3C0E62E167DA02DC4D91AB2B21E';
		$this->reqUrl = 'http://127.0.0.1/api/remit';// 测试环境
		$this->query_reqUrl = 'http://127.0.0.1/api/queryRemitResult';
	}

	public function create_remit(){
		$Rsa = new Rsa();
		$data['orderNo'] = date('YmdHis') .rand(10000,99999);
		$data['version'] = 'V3.3.0.0';
		$data['charsetCode'] = 'UTF-8';

		$data['bankCode'] = 'ICBC';
		$data['merchNo'] = $this->merchNo;
		$data['bankAccountName'] = '户名';
		$data['bankAccountNo'] = '6217002200015935552';
		$data['amount'] = '100';
		$data['notifyUrl'] = 'http://127.0.0.1remit.php?rec=callback';
		$data['sign'] = create_sign($data,$this->key);
		
		$json = json_encode_ex($data);
		$dataStr = $Rsa->encode_remit($json);
 		$param = 'data=' . urlencode($dataStr) . '&merchNo=' . $this->merchNo;

 		$result = wx_post($this->reqUrl,$param);
 		$rows = json_to_array($result,$this->key);

 		if ($rows['stateCode'] == '00'){
 			echo "代付创建成功,以下是订单数据</br>";
 			P($rows); 
 		}else{
 			echo "错误代码：" . $rows['stateCode'] . ' 错误描述:' . $rows['msg'];

 		}
 
	}

	public function query(){
		$Rsa = new Rsa();
		$data['orderNo'] = '201808011041370902x0S';
		$data['remitDate'] = '2018-08-01';
		$data['merchNo'] = $this->merchNo;
		$data['amount'] = '10000';
		$data['sign'] = create_sign($data,$this->key);

		$json = json_encode_ex($data);
		$dataStr = $Rsa->encode_remit($json);
 		$param = 'data=' . urlencode($dataStr) . '&merchNo=' . $this->merchNo;

 		$result = wx_post($this->query_reqUrl,$param);
 		$rows = json_to_array($result,$this->key);
 		if ($rows['stateCode'] == '00'){
 			echo "代付订单查询成功,以下是订单数据</br>";
 			P($rows);
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
			log_write("收到代付回调通知");
	 		log_write(PS($rows));
	 			
		}
		echo "SUCCESS";
	}


}
header("Content-type: text/html; charset=utf-8");
$rec = isset($_GET['rec']) ? $_GET['rec'] : '';

if ($rec == 'remit'){
	$remit = new remit();
	$remit->create_remit();
}

if ($rec == 'query'){
	$remit = new remit();
	$remit->query();
}

if ($rec == 'callback'){
	$remit = new remit();
	$remit->callback();
}

 
?>