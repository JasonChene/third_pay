<?php
$md5Key='ggeWq1BO6HpXmmfUYzA4y1yPkWHadj';
$url='http://localhost/Pay/YZfb/dfPay';

$sn=date("YmdHis").rand(111,999);
	$data= array(
            "memberid" => '10007',//商户号
            "orderid" => $sn,//订单号
			'notifyUrl'=>'http://localhost/css.php',
			"cardHolder" => '张三',//收款人姓名
			"bankCardNo" => '6210817207018555465',//收款人银行卡号
			"bankName" => '中国建设银行',//收款人银行账号开户行
			"bankProvince" => '广州',//开户所在省
			"payAmount" => '18.86',//需要代付的金额
			"bankCity" => '深圳',////开户所在市
			"bankBranchName" =>'西乡支行',//支行名称
			"send_type" =>'D0',//代付方式
			"cardId" =>'430422199508176012',//身份证
			"mobile" =>'13928955596',//预留手机号
			"bankCode" => '105584001434' //银联号
			
        );/*

$url='http://localhost/Pay/YZfb/dfquery';

		$data= array(
            "memberid" => '10007',//商户号
            "orderid" =>'20180801223104239'
        );*/
ksort($data);
$pri='./key/10007_pri.pem';


echo md5Encrypt($data,$md5Key).'<br/>';
$data['sign'] = signRsas(md5Encrypt($data,$md5Key), $pri);//签名

echo "<pre>";
print_r($data);
echo "</pre>";


$res =   httpClient($data, $url);
//echo $res;
echo $res.'<br/>';

$res=json_decode($res,true);
$sign=$res['sign'];
unset($res['sign']);
$pub='./key/pub.pem';
ksort($res);
echo verifys(md5Encrypt($res,$md5Key),$sign,$pub);


function md5Encrypt($data,$md5Key){
       $sign='';
		foreach ($data as $k=>$v){
			
			$sign.=$k.'='.$v."&";
		}
	   $sign=$sign."key=".$md5Key;
	   

       $md5 = strtoupper(md5($sign));
        return $md5;
}

function verifys($str,$sign,$public)
{
    $pem = file_get_contents($public);
    $public_key = openssl_get_publickey($pem);
    if (empty($sign)) return false;
    $result = (bool)openssl_verify($str, base64_decode($sign), $public_key);
    openssl_free_key($public_key);
    return $result;
}
function signRsas($source, $private)
{
    /* $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" .
     wordwrap($private_Key, 64, "\n", true) .
     "\n-----END RSA PRIVATE KEY-----";

     extension_loaded('openssl') or die('php需要openssl扩展支持');
     */
    $private_key = file_get_contents($private);

    /* 提取私钥 */
    $privateKey = openssl_get_privatekey($private_key);

    ($privateKey) or die('密钥不可用');

    openssl_sign($source, $encode_data, $privateKey);

    openssl_free_key($privateKey);

    $signToBase64 =base64_encode($encode_data);


   // $signToBase64 .= '$SHA256';


    return $signToBase64;
    

}
    /**
     * POST方式访问接口
     * @param string $data
     * @return mixed
     */
     function httpClient($data, $Url) {
        $postdata = http_build_query($data);
		echo $postdata;
		
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $Url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $res = curl_exec($ch);
            curl_close($ch);
            return $res;
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            return false;
        }
    }
	
    

