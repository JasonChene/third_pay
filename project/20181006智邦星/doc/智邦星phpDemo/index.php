<?php
	date_default_timezone_set("Asia/Shanghai");
	$currentTime = date("YmdHis");
	$orderNo="SH".$currentTime;//流水号
?>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no,minimal-ui">
	<meta name="format-detection" content="telephone=no" />
	<title>支付对接实例（demo）</title>
	<style type="text/css">
	body{padding:0;margin:0;color:#333;font-size:14px;font-family:微软雅黑;}
	ul,li{padding:0;margin:0;list-style:none;}
	.top{width:100%; height:60px;position: fixed; top:0;left:0;z-index:100;}
	.top-head{background-color:#18b4ed; text-align:center;color:#fff; height:45px; line-height:45px;}
	.top-nav{ height:45px; line-height:45px; background-color:#f0f0f0;}
	.top-nav li{width:50%; float:left;color:#777;border-bottom:2px solid #777; text-align:center;}
	.top-nav li.thisclass{border-bottom:2px solid #e70000;color: #e70000;background-color:#fff;}
	.content{width:98%;padding:1%;position:absolute; top:50px;left:0;z-index:99;}
	.ui-form-item{ height:45px; line-height:40px; width:100%;padding:5px 0; border-bottom:1px solid #e0e0e0;}
	.ui-form-item label{width:100px; display:block; float:left; line-height:20px;position:absolute;}
	input,select{ height:40px;line-height:40px; float:left;color:#18b4ed;width:100%;font-size:13px; border:0; padding-left:100px;}
	.ui-btn-lg{ background-color:#18b4ed;color:#fff; height:45px; line-height:45px; text-align:center;border-radius:3px; margin-top:10px;cursor:pointer;}
	.top-nav li{cursor:pointer;}
	</style>
</head>
<body>
	<div style="width:100%; min-width:320px;">
	<div class="top">
		<div class="top-nav">
			<ul>
				<li class="thisclass">支付接口</li>
				<li>查询接口</li>
			</ul>
		</div>
	</div>
	<div class="content">
		<div class="content-pay">
			<form name="payForm" id="payForm" action="/pay.php" autocomplete="off" method="post" target="_blank">
				<div class="ui-form-item">
					<label>
						<div>支付地址</div>
						<div style="font-size:12px;"> ( payUrl )</div>
					</label>
					<input name="payUrl" type="text" value="http://www.zbxpay.com/pay" readonly="readonly"/>
				</div>
				<div class="ui-form-item">
					<label>
						<div>充值金额</div>
						<div style="font-size:12px;"> ( amount )</div>
					</label>
					<input name="amount" type="text"  placeholder="充值金额 ( 单位元，两位小数 ) " value="1.00"/>
				</div>
				<div class="ui-form-item">
					<label>
						<div>当前时间</div>
						<div style="font-size:12px;"> ( currentTime )</div>
					</label>
					<input name="currentTime" type="text" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" placeholder="当前时间 ( 格式为：yyyyMMddHHmmss，例如：20180101235959 ) " value="<?php echo $currentTime; ?>"/>
				</div>
				<div class="ui-form-item">
					<label>
						<div>商 户 号</div>
						<div style="font-size:12px;"> ( merchant )</div>
					</label>
					<input name="merchant" type="text" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" placeholder="请输入商户号" value="866001"/>
				</div>
				<div class="ui-form-item">
					<label>
						<div>商户密钥</div>
						<div style="font-size:12px;"> ( key )</div>
					</label>
					<input name="key" type="text" placeholder="请输入商户密钥" value="53021e2cae854d2e8fd32e2e89e5ec1c" autocomplete="off"/>
				</div>
				<div class="ui-form-item">
					<label>
						<div>异步回调</div>
						<div style="font-size:12px;"> ( notifyUrl )</div>
					</label>
					<input name="notifyUrl" type="text" placeholder="异步回调地址 ( 返回支付结果 ) " value="http://pay.zbxzf.com/notifyUrl.php" autocomplete="off"/>
				</div>
				<div class="ui-form-item">
					<label>
						<div>订单号</div>
						<div style="font-size:12px;"> ( orderNo )</div>
					</label>
					<input name="orderNo" type="text" placeholder="商户订单号" value="<?php echo $orderNo; ?>" autocomplete="off"/>
				</div>
				<div class="ui-form-item">
					<label>
						<div>支付类型</div>
						<div style="font-size:12px;"> ( payType )</div>
					</label>
					<div style="padding-left:100px;">
						<select name="payType" style=" padding-left:0;">
							<option value="wxpay">微信支付</option>
							<option value="alipay" selected>支付宝支付</option>
							<option value="qqpay">QQ钱包</option>
						</select>
					</div>
				</div>
				<div class="ui-form-item">
					<label>
						<div>备注信息</div>
						<div style="font-size:12px;"> ( remark )</div>
					</label>
					<input name="remark" type="text" placeholder="备注信息 ( 该备注信息会通过异步回调接口回调 ) " value="12345" autocomplete="off"/>
				</div>
				<div class="ui-form-item">
					<label>
						<div>同步回调</div>
						<div style="font-size:12px;"> ( returnUrl )</div>
					</label>
					<input name="returnUrl" type="text" placeholder=" 	同步回调地址 ( 支付成功或订单超时自动跳转的地址 ) " value="http://pay.zbxzf.com/returnUrl.php" autocomplete="off"/>
				</div>
				<div class="ui-form-item">
					<div class="ui-btn-lg" name="pay_submit">提交支付</div>
				</div>
			</form>
		</div>
		<div class="content-check" style="display:none;">
			<form name="checkForm" id="checkForm" action="/query.php" autocomplete="off" method="post" target="_blank">
				<div class="ui-form-item">
					<label>
						<div>查询地址</div>
						<div style="font-size:12px;"> ( queryUrl )</div>
					</label>
					<input name="queryUrl" type="text" value="http://www.zbxpay.com/query" readonly="readonly"/>
				</div>
				<div class="ui-form-item">
					<label>
						<div>创建时间</div>
						<div style="font-size:12px;"> ( createTime )</div>
					</label>
					<input name="createTime" class="time" type="text" value="20180412162726"  placeholder="创建时间 ( 格式为：yyyyMMddHHmmss，例如：20180101235959 ) "/>
				</div>
				<div class="ui-form-item">
					<label>
						<div>当前时间</div>
						<div style="font-size:12px;"> ( currentTime )</div>
					</label>
					<input name="currentTime" value="<?php echo $currentTime; ?>" type="text"  placeholder="当前时间 ( 格式为：yyyyMMddHHmmss，例如：20180101235959 )）"/>
				</div>
				<div class="ui-form-item">
					<label>
						<div>商户号</div>
						<div style="font-size:12px;"> ( merchant )</div>
					</label>
					<input name="merchant" type="text" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" placeholder="请输入商户号" value="866001"/>
				</div>
				<div class="ui-form-item">
					<label>
						<div>商户订单号</div>
						<div style="font-size:12px;"> ( orderNo )</div>
					</label>
					<input name="orderNo" type="text"  placeholder="商户订单号" value="SH20180412162720"/>
				</div>
				<div class="ui-form-item">
					<label>
						<div>密&nbsp;钥</div>
						<div style="font-size:12px;"> ( key )</div>
					</label>
					<input name="key" type="text" placeholder="请输入商户密钥" value="53021e2cae854d2e8fd32e2e89e5ec1c" autocomplete="off"/>
				</div>
				<div class="ui-form-item ui-btn-wrap">
					<div class="ui-btn-lg ui-btn-danger" name="check_submit">提交查询</div>
				</div>
			</form>
		</div>
	</div>
	</div>
	<script type="text/javascript" src="./statics/js/zepto.min.js"></script>
	<script type="text/javascript">
	$(function(){
		$(".top-nav li").on("click",function(){
			$(this).parent().find("li").removeClass("thisclass");
			$(this).addClass("thisclass");
			var index = $(this).index();
			$(".content > div").hide();
			$(".content > div").eq(index).show();
		});
	})
	$("div[name='pay_submit']").on("click",function(){
		$("form[name='payForm']").submit();
	});
	
	$("div[name='check_submit']").on("click",function(){
		$("form[name='checkForm']").submit();
	});
	
	//去掉input输入内容的首尾空格
	$("input.time").on("input",function(){
		var str = $(this).val();
		str = str.replace(/\:|\-|\s/g,""); 
		if(!!str){
			$(this).val(str.trim());
		}
	});
	
	</script>
</body>
</html>