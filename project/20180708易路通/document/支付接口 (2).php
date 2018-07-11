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
	{
		if($start['PayType']== "1"&&$start['SubpayType']=="10"){     //如果是微信扫码生成二维码
			include 'phpqrCode/phpqrcode.php';  //引入phpqrcode类文件
			$value = $json->data; //二维码内容
			$errorCorrectionLevel = 'L';//容错级别
			$matrixPointSize = 6;//生成图片大小
			//生成二维码图片
			QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
			$logo = 'logo.png';//准备好的logo图片  需要加入到二维码中的logo
			$QR = 'qrcode.png';//已经生成的原始二维码图
			if ($logo !== FALSE) {
				$QR = imagecreatefromstring(file_get_contents($QR));
				$logo = imagecreatefromstring(file_get_contents($logo));
				$QR_width = imagesx($QR);//二维码图片宽度
				$QR_height = imagesy($QR);//二维码图片高度
				$logo_width = imagesx($logo);//logo图片宽度
				$logo_height = imagesy($logo);//logo图片高度
				$logo_qr_width = $QR_width / 5;
				$scale = $logo_width/$logo_qr_width;
				$logo_qr_height = $logo_height/$scale;
				$from_width = ($QR_width - $logo_qr_width) / 2;
				//重新组合图片并调整大小
				imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
				$logo_qr_height, $logo_width, $logo_height);
			}
			imagepng($QR, $start['payordernumber'].'.png');
			echo '<img src="'.$start['payordernumber'].'.png">';
		}
		else echo "<script type='text/javascript'>window.location.href='".$json->data."'</script>"; 
	}
?>