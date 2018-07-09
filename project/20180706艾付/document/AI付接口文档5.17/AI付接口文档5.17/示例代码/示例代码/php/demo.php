<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>使用网关充值流程范例</title>
<style>
	input[type="text"]{width: 300px;}
	input[type="submit"]{width: 80px;}
</style>
</head>
<body>
<?php
class Aifu {

	public $merchant_no = "144710001674";						//商户号
	public $key = "8359aaa5-ad06-11e7-9f73-71f4466";			//商户接口秘钥
	
	public function getBankList(){
			
		$mode = "WEBPAY";										//模式
		//MD5签名
		$md5Src = "merchant_no=" . $this->merchant_no . "&mode=" . $mode . "&key=".  $this->key;
		$sign = md5($md5Src);
				
		$parameter = array(
            'merchant_no'	=> $this->merchant_no,
            'mode'	=> 'WEBPAY', 								// WEBPAY:表示获取支持WEB网站的银行列表  WAPPAY:表示获取支持手机网站的银行列表
            'sign' => $sign
        );

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://pay.all-inpay.com/gateway/queryBankList");
		curl_setopt($curl, CURLOPT_POST, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query( $parameter ));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
		$response = curl_exec($curl);
		curl_close($curl);

		return json_decode($response, true);
	}
}

$aifu = new Aifu();
$bank=$aifu->getBankList();

?>
<center>
<b>使用网关充值流程范例</b>
<form action="demo2.php" method="post" id="form" >
	<table cellpadding="3" width="800">
		<tr><td>金额：</td><td width="700"><input type="text" name="amount" value="100" /></td></tr>
		<tr><td>选择银行：</td><td>
		<?php
		foreach($bank["bank_list"] as $k=>$v)
		{
			echo '<label><input type="radio" name="bank_code" value="'.$v["BANK_CODE"].'" /><img src="'.$v["LOGO_URL"].'"></label>';
		}
		?>
		</td></tr>
		<tr><td colspan="2" align="center"><input type="submit" value="提交"></td></tr>
	</table>
</form>
</center>
</body>
</html>