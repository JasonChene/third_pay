<?php
$md5Key='ggeWq1BO6HpXmmfUYzA4y1yPkWHadj';

$url='IP端口/Pay/YZfb/Pay';
$sn=date("YmdHis").rand(111,999);
$data = array(
    "pay_memberid" =>'10007',//商户号
    "pay_orderid" => $sn,//订单号
    "pay_amount" => '10.00',// 订单金额
    "pay_applydate" =>date("YmdHis"),// 订单时间
    "pay_bankcode" => 'ALIPAY',  // 支付类型code
    "pay_notifyurl" =>'localhost/css.php',// 异步跳转链接
    "pay_callbackurl" => 'localhost/css.php' //同步跳转链接
);
ksort($data);
$data['pay_md5sign'] = md5Encrypt($data, $md5Key);//签名

echo "<pre>";
print_r($data);
echo "</pre>";
/*
$url='IP端口/Pay/YZfb/orderQuery/';
     

$data = array(
    "memberid" =>'10007',//商户号
    "orderid" => '153060626915503552'//订单号
   
);
ksort($data);
$data['sign'] = md5Encrypt($data, $md5Key);//签名

echo "<pre>";
print_r($data);
echo "</pre>";*/
$res =   httpClient($data, $url);

echo $res.'<br/>';

echo "<pre>";
print_r(json_decode($res,true));
echo "</pre>";

die;

function md5Encrypt($data, $md5Key){
    $md5Str = '';
    foreach ($data as $key => $value) {
        if($value != ''){
            $md5Str.=$key.'=>'.$value.'&';
        }

    }
    $md5Str.='key='.$md5Key;

    //debug($md5Str);
    $md5 = strtoupper(md5($md5Str));
    return $md5;
}

/*
   function md5Encrypt_new($data, $md5Key){
        $md5Str = '';
        foreach ($data as $key => $value) {
            if($value != ''){
                $md5Str.=$key.'=>'.$value.'&';
            }
    
        }
        $md5Str.='key='.$md5Key;
        //debug($md5Str);
        $md5 = strtoupper(md5($md5Str));
        return $md5;
    }
    */
    /**
     * POST方式访问接口
     * @param string $data
     * @return mixed
     */
     function httpClient($data, $Url) {
        $data = http_build_query($data);
	
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $Url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $res = curl_exec($ch);
            curl_close($ch);
            return $res;
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            return false;
        }
    }
	
    

