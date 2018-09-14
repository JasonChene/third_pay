<?php
echo '<pre/>';
date_default_timezone_set('Asia/Shanghai');
$a = pay(); var_dump($a);
function pay(){ 
        $keys = '8bcRdkglHDxrmkOFWkwEFYcylmSMrgkQ';
        $mchid = 'jc00000205';
        $url = '120.78.245.232/index.php/686cz/trade/pay'; 
        $callbackUrl = 'http://center.cdanyida.com/test.php';
        $data = array(
            'code' => $mchid,
            'tradeAmount'=> '50.00', 
            'outOrderNo' => time(),
            'payCode' => 'wxpay',//若是使用支付宝就填alipay
            'goodsClauses'=>'test',
            'notifyUrl'=>$callbackUrl
        );
        

        $data['sign']=getSign($data,$keys);
        $info =  https_request($url,($data));
        var_dump($info);
	
}


function getSign($data,$keys){      
   
    ksort($data);
    $str = '';
    foreach ($data as $key => $value) {
     if($value != ''){
         $str .= $key.'='.$value.'&'; 
            
     }   
    }


        $sign = md5($str.'key='.$keys);

     
        return $sign;

}


 function https_request($url,$data = null){
	$curl = curl_init();
	
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	if (!empty($data)){
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	}
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}


 



