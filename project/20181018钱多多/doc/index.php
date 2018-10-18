<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
//post 提交数据生成支付订单
	function ewmpost($url_ewm,$data){
		$ch = curl_init($url_ewm);
		$header = array('apikey: safepay',);
		curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $responseTextt = curl_exec($ch);
        return $responseTextt;
		}
     /** 获取当前时间戳，精确到毫秒 */
function microtime_float()
{
  list($usec, $sec) = explode(" ", microtime());
  return ((float)$usec + (float)$sec);
}
/** 格式化时间戳，精确到毫秒，x代表毫秒 */
function microtime_format($tag, $time)
{
  list($usec, $sec) = explode(".", $time);
  $date = date($tag,$usec);
  return str_replace('x', $sec, $date);
}
function getMillisecond() {
list($s1, $s2) = explode(' ', microtime());
return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
} 
   /*  //获取支付方式
    $pay_fs=$_POST['pay_fs'];
    if($pay_fs==""){
		$pay_fs=$_GET['pay_fs'];
		}
	if ($pay_fs!="") {  */
	
	$datastring=array(
	    'pay_fs'=>'weixin',//$pay_fs,//'weixin',//'weixin',//(alipay; qq)
        'pay_MerchantNo'=>'7EC887686D677A19',
        'MerchantNo'=>'10000000',
        'pay_orderNo'=>getMillisecond(),
        'pay_Amount'=>'1.00',
        'pay_ProductName'=>'',
        'pay_NotifyUrl'=>'http://120.24.7.139/cssj/merchant/',
        'pay_ewm'=>'No',
        'url_ewm'=>'http://103.218.3.102:8091/pay1.0/',
        'key'=>'DB432A05DD58C09D934C8007B831CB17',
        'tranType'=>"2",
	);

 function verifyString($data)
{
	ksort($data);
	unset($data['sign']);
	$arr	= array();
	foreach($data AS $k => $v){
		if($k != 'sign'){
			$arr[]	= $k."=".$v;
		}
	}
	
	return implode("&", $arr); 
}

	switch($datastring['pay_fs']){
		//代付查询
		case 'cx':
		     $str=$datastring['pay_fs']."".$datastring['pay_MerchantNo']."".$datastring['pay_orderNo']."".$datastring['key'];
	         $sign=md5($str);
			 $data=array(
			     ' pay_fs'=>$datastring['pay_fs'],
				 'pay_MerchantNo'=>$datastring['MerchantNo'],
				 'pay_orderNo'=>$datastring['pay_orderNo'],
				 'sign'=>$sign
			 );
			 break;
			 //代付
		case 'df':
		    $dfdata=array(
		     'acctName'=>'张三',
             'acctNo'=>'622619238107397',
             'bankName'=>'民生银行',
             'retUrl'=>'http://120.24.7.139/cssj/merchant/',
	         'bankCode'=>'CMBC'
			 );
	         $str=$datastring['pay_fs']."".$datastring['pay_MerchantNo']."".$datastring['pay_orderNo']."".$datastring['pay_Amount']."".$dfdata['acctNo']."".$datastring['key'];
	         $sign=md5($str);
	         $data=array(
			     'pay_fs'=>$datastring['pay_fs'],
				 'pay_MerchantNo'=>$datastring['MerchantNo'],
				 'pay_orderNo'=>$datastring['pay_orderNo'],
				 'pay_Amount'=>$datastring['pay_Amount'],
				 'pay_acctName'=>$dfdata['acctName'],
				 'pay_acctNo'=>$dfdata['acctNo'],
				 'pay_bankName'=>$dfdata['bankName'],
				 'pay_bankCode'=>$dfdata['bankCode'],
				 'pay_retUrl'=>$dfdata['retUrl'],
				 'sign'=>$sign
				 );
			 break;
			 //订单查询
		case 'ordercx':
		     $str=$datastring['pay_fs']."".$datastring['pay_MerchantNo']."".$datastring['pay_orderNo']."".$datastring['key'];
			 $sign=md5($str);
			 $data=array(
			     ' pay_fs'=>$datastring['pay_fs'],
				 'pay_MerchantNo'=>$datastring['MerchantNo'],
				 'pay_orderNo'=>$datastring['pay_orderNo'],
				 'sign'=>$sign
			 );
			 break;
			 //微信、支付宝、qq支付请求
		default:
		     $str=$datastring['pay_fs']."".$datastring['pay_MerchantNo']."".$datastring['pay_orderNo']."".$datastring['pay_Amount']."".$datastring['pay_NotifyUrl']."".$datastring['pay_ewm']."".$datastring['key'];
			 $sign=md5($str);
			 $data=array(
			      'pay_fs'=>$datastring['pay_fs'],
				  'pay_MerchantNo'=>$datastring['MerchantNo'],
				  'pay_orderNo'=>$datastring['pay_orderNo'],
				  'pay_Amount'=>$datastring['pay_Amount'],
				  'pay_ProductName'=>$datastring['pay_ProductName'],
				  'pay_NotifyUrl'=>$datastring['pay_NotifyUrl'],
				  'pay_ewm'=>$datastring['pay_ewm'],
				  'tranType'=>$datastring['tranType'],
				  'sign'=>$sign
				  ); 
			 break;
		}
		//获取结果JSON数据
		$responseText=ewmpost($datastring['url_ewm'],$data);
		echo $responseText."\n\n";
		//解析JSON数据
       $txt= json_decode($responseText);
	   $qrcode=$txt->pay_Code;
	   echo $qrcode;
	/*}else{
		echo "请选择收款方式";
		}*/
?>
