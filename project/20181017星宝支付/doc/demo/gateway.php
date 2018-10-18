<?PHP

function topay($url,$data,$type,$return_url){
	$data=getHtml($url,$data);
	$de_json =json_decode($data);
	$msg=$de_json->msg;
  	$code=$de_json->code;
  	$data=$de_json->data;
	if($code==200){
		$payurl=$data->image;
		$mark=$data->record;
		$money=$data->money;
     	$order_num=$data->order_num;
		if($type=="3"){
			$payurl=urlencode($payurl);
		}
		gotoPay($money,$payurl,$mark,$type,$order_num,$return_url);
	}else{
		echo $msg;
	}
}

function gotoPay($money,$pay_url,$trade_no,$type,$order_num,$return_url){
	echo "<form style='display:none;' id='form1' name='form1' method='post' action='payhelper/pay.php'>
			  <input name='money' type='text' value='{$money}' />
			  <input name='pay_url' type='text' value='{$pay_url}'/>
			  <input name='trade_no' type='text' value='{$trade_no}'/>
			  <input name='type' type='text' value='{$type}'/>
              <input name='order_num' type='text' value='{$order_num}'/>
              <input name='return_url' type='text' value='{$return_url}'/>
			</form>
			<script type='text/javascript'>function load_submit(){document.form1.submit()}load_submit();</script>";
}

function getHtml($url,$data=''){
	$ch = curl_init($url) ;
	$header[]= 'Mozilla/5.0 (Linux; U; Android 7.1.2; zh-cn; GiONEE F100 Build/N2G47E) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30';
	if(!empty($data)){
		curl_setopt($ch, 47, 1);  
		curl_setopt($ch, 10015, $data);
	}
	curl_setopt($ch,10023,$header);
	curl_setopt($ch, 64, FALSE); // 对认证证书来源的检查
	curl_setopt($ch, 81, FALSE); // 从证书中检查SSL加密算法是否存在
	curl_setopt($ch, 19913, true) ;
	curl_setopt($ch, 19914, true) ;
	curl_setopt($ch, 52,1);  
	curl_setopt($ch, 13, 60);
	ob_start();
	@$data = curl_exec($ch);
	ob_end_clean();
	curl_close($ch); 
	return $data;
}
?>