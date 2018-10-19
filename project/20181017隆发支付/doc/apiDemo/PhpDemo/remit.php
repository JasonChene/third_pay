<?php 
include('util.php');

class remit{
	private $merchNo;
	private $key;
	private $reqUrl;
	public function __construct(){
		$this->merchNo = 'LFP201808250000';
		$this->key = '7D7E768BB4CB7EBCE6E3B067A6351342';
		$this->reqUrl = 'http://47.94.6.240:9003/api/remit';// 测试环境
		$this->query_reqUrl = 'http://47.94.6.240:9003/api/queryRemitResult';
		$this->version = 'V3.6.0.0';
	}

	public function create_remit(){
		$Rsa = new Rsa();
		$data['orderNo'] = date('YmdHis') .rand(10000,99999);
		$data['bankCode'] = 'BOC';
		$data['merchNo'] = $this->merchNo;
		$data['bankAccountName'] = '张三';
		$data['bankAccountNo'] = '621661280000447287';
		$data['amount'] = '1000';
		$data['notifyUrl'] = 'http://127.0.0.1remit.php?rec=callback';
		$data['sign'] = create_sign($data,$this->key);
		
		$json = json_encode_ex($data);
		$dataStr = $Rsa->encode_remit($json);
 		$param = 'data=' . urlencode($dataStr) . '&merchNo=' . $this->merchNo . '&version=' . $this->version;

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
		$data['orderNo'] = '20180826101006258rnrZ';
		$data['remitDate'] = '2018-08-06';
		$data['merchNo'] = $this->merchNo;
		$data['amount'] = '10000';
		$data['sign'] = create_sign($data,$this->key);

		$json = json_encode_ex($data);
		$dataStr = $Rsa->encode_remit($json);
 		$param = 'data=' . urlencode($dataStr) . '&merchNo=' . $this->merchNo . '&version=' . $this->version;

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