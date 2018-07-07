<?php
	if(empty($_POST["bank_code"]))
	{
?>
		<center>
			<b>请选择银行</b>
			<form>
				<input type="button" value="返回上页" onclick="history.back()">
			</form>
		</center>
<?php
		exit;
	}
	if(intval($_POST["amount"]) <100)
	{
?>
		<center>
			<b>金额不能小于100</b>
			<form>
				<input type="button" value="返回上页" onclick="history.back()">
			</form>
		</center>
<?php
		exit;		
	}	
	$version = "v1";									//接口版本
	$merchant_no = "144710001674";						//商户号
	$order_no = time();									//商户订单号
	$goods_name = "充值";								//商品名称
	$order_amount = $_POST["amount"];								//订单金额
	$backend_url = "";									//支付结果异步通知地址
	$frontend_url = "";									//支付结果同步通知地址
	$reserve = "";										//商户保留信息
	$pay_mode = "01";									//支付模式
	$bank_code = $_POST["bank_code"];									//银行编号(需先调用获取网关银行列表取得银行编号) 范例:ABC为农业银行 . 备注:少部分银行不支持境外IP,若出现风控受限信息请更换中国境内IP
	$card_type = "0";									//允许支付的银行卡类型
	$goods_name = base64_encode($goods_name);			//Base64编码
	$key = "8359aaa5-ad06-11e7-9f73-71f4466";		//商户接口秘钥

    //MD5签名
    $src = "version=" . $version . "&merchant_no=" . $merchant_no . "&order_no="
            . $order_no . "&goods_name=" . $goods_name . "&order_amount=" . $order_amount
            . "&backend_url=" . $backend_url . "&frontend_url="
            . $frontend_url . "&reserve=" . $reserve
            . "&pay_mode=" . $pay_mode . "&bank_code=" . $bank_code . "&card_type="
            . $card_type;
    $src .= "&key=" . $key;
    $sign = md5($src);

    //接口地址
	$url = "https://pay.all-inpay.com/gateway/pay.jsp";

?>
<b>订单处理中,请稍后</b>
<form action="<?=$url?>" method="POST" id="form" target="_blank">
		<input type="hidden" name="version" value="<?=$version?>" />
		<input type="hidden" name="merchant_no" value="<?=$merchant_no?>" />
		<input type="hidden" name="order_no" value="<?=$order_no?>" />
		<input type="hidden" name="goods_name" value="<?=$goods_name?>" />
		<input type="hidden" name="order_amount" value="<?=$order_amount?>" />
		<input type="hidden" name="backend_url" value="<?=$backend_url?>" />
		<input type="hidden" name="frontend_url" value="<?=$frontend_url?>" />
		<input type="hidden" name="reserve" value="<?=$reserve?>" />
		<input type="hidden" name="pay_mode" value="<?=$pay_mode?>" />
		<input type="hidden" name="bank_code" value="<?=$bank_code?>" />
		<input type="hidden" name="card_type" value="<?=$card_type?>" />
		<input type="hidden" name="sign" value="<?=$sign?>" />
</form>
<script>
	document.getElementById("form").submit();
</script>