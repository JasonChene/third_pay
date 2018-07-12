<?php          //by 3huai        
	header("Content-type:text/html;charset=utf-8");
	$session = "";//系统提供 
	$appid = "";//系统提供
	$key = ""; //系统提供
	$qingqiuURl  = "http://bank.fjelt.com/pay/Rest";
	$start['amount'] = 30000;          
	$start['payordernumber'] = "2017".time();//订单号
	$start['fronturl']       = "";//前台回调地址  需要订单号的在回调地址加."?payordernumber=".$start['payordernumber']
	$start['backurl']        = "";//后台回调地址
	$start['body']           = "";//商品名称
	$start['PayType']        = "2";//支付方式
	$start['SubpayType']     = "15";//具体支付方式
	$start_str = json_encode($start);  
	$aes_start_str = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key , $start_str, MCRYPT_MODE_CBC, $key);
	$aes_start_str1 = base64_encode($aes_start_str);
	$data = str_replace(array('+','/'),array('-','_'),$aes_start_str1);

	
	$post_row['appid']  = $appid;
	$post_row['method'] = "masget.pay.compay.router.font.pay";
	$post_row['format'] = "json";
	$post_row['data']   =  $data;
	$post_row['v']      = "2.0";
	$post_row['timestamp'] = date("Y-m-d H:m:s",time());
	$post_row['session']   = $session;
	$post_row['sign'] =strtolower(md5($key.$post_row['appid'].$post_row['data'].$post_row['format'].$post_row['method'].$post_row['session'].$post_row['timestamp'].$post_row['v'].$key));
	$postdata = http_build_query($post_row);
	$options = array( 'http' => array( 'method' => 'POST','header' =>'Content-type:application/x-www-form-urlencoded','content' => $postdata,'timeout' =>  60 // 超时时间（单位:s）    
	)  );
	$context = stream_context_create($options);
	$result = file_get_contents($qingqiuURl, false, $context);
	$json=json_decode($result); //{"ret":0,"message":"成功","data":"http://bank.fjelt.com/unionpay/CustInput?id="}
	if($json->ret!='0')          
	echo $json->message;
	else          
	echo "<script>window.location =/".$json->data.";</script>";
	?>