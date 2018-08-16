<?php
$ReturnArray = array( // 返回字段
	"memberid" => $_POST["memberid"], // 商户号，商户后台【API管理-API开发文档】中获取
	"orderid" =>  $_POST["orderid"], // 订单号
	"amount" =>  $_POST["amount"], // 交易金额
	"datetime" =>  $_POST["datetime"], // 交易时间
	"returncode" => $_POST["returncode"], //状态码
	'transaction_id' => $_POST["transaction_id"] //支付流水号
);
$attach = $_POST["attach"]; // 自定义参数
$Md5key = "gshcbcx***********flwr8wd7"; //apikey(密钥)，商户后台【API管理-API开发文档】中获取

ksort($ReturnArray);
reset($ReturnArray);
$md5str = "";
foreach ($ReturnArray as $key => $val) {
	$md5str = $md5str . $key . "=" . $val . "&";
}
$sign = strtoupper(md5($md5str . "key=" . $Md5key));
if ($sign == $_POST["sign"]) {

	if ($ReturnArray["returncode"] == "00") {
		/**
		 * TODO 商户自行处理逻辑
		 */
		$str = "交易成功！订单号：".$_POST["orderid"];
		file_put_contents("success.txt",$str."\n", FILE_APPEND);
		echo "ok";
	}
}

?>