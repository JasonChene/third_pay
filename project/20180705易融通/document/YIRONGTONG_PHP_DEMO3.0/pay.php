<?php 
            $apiurl = $_POST[txtUrl];/*接口提交地址*/
			$version=$_POST[txtversion];/*接口版本号,目前固定值为3.0*/
			$method=$_POST[txtmethod];/*接口名称: Gt.online.pay*/
            $partner = $_POST[txtpartner];//商户id,由API分配
			$banktype =$_POST[txtbanktype];//银行类型 default为跳转到接口进行选择支付
			$paymoney =$_POST[txtpaymoney];//单位元（人民币）,两位小数点
			$ordernumber =$_POST[txtordernumber];//商户系统订单号，该订单号将作为接口的返回数据。该值需在商户系统内唯一
			$callbackurl = $_POST[txtcallbackurl];//下行异步通知的地址，需要以http://开头且没有任何参数
            $hrefbackurl = $_POST[txthrefbackurl];//下行同步通知过程的返回地址(在支付完成后接口将会跳转到的商户系统连接地址)。注：若提交值无该参数，或者该参数值为空，则在支付完成后，接口将不会跳转到商户系统，用户将停留在接口系统提示支付成功的页面。
			$goodsname=$_POST[txtgoodsname];//商品名称。若该值包含中文，请注意编码
			$attach = $_POST[txtattach];//备注信息，下行中会原样返回。若该值包含中文，请注意编码
			$isshow=$_POST[txtisShow];//该参数为支付宝扫码、微信、QQ钱包专用，默认为1，跳转到网关页面进行扫码，如设为0，则网关只返回二维码图片地址供用户自行调用
            $key = $_POST[txtKey];//商户Key,由API分配
            
            $signSource = sprintf("version=%s&method=%s&partner=%s&banktype=%s&paymoney=%s&ordernumber=%s&callbackurl=%s%s", $version,$method,$partner, $banktype, $paymoney, $ordernumber, $callbackurl, $key);
            $sign = md5($signSource);//32位小写MD5签名值，UTF-8编码
            $postUrl = $apiurl. "?version=".$version;
			$postUrl.="&method=".$method;
			$postUrl.="&partner=".$partner;
			$postUrl.="&banktype=".$banktype;
            $postUrl.="&paymoney=".$paymoney;
            $postUrl.="&ordernumber=".$ordernumber;
            $postUrl.="&callbackurl=".$callbackurl;
            $postUrl.="&hrefbackurl=".$hrefbackurl;
			$postUrl.="&goodsname=".$goodsname;
            $postUrl.="&attach=".$attach;
			$postUrl.="&isshow=".$isshow;
            $postUrl.="&sign=".$sign;
			header ("location:$postUrl");
?>