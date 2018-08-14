<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>支付</title>
<meta name="keywords" content="" />
<link href="css/yeepaytest.css" type="text/css" rel="stylesheet" />
</head>
<script src="scripts/jquery.min.js" type="text/javascript"></script>
<body>
	<table width="60%" border="0" align="center" cellpadding="0"
		cellspacing="0" style="border: solid 1px #40506b;">
		<tr>
			<td>
				<form method="post" action="payMac.php">
					<table width="100%" border="0" align="center" cellpadding="5"
						cellspacing="1" style="border-spacing: 0;">
						<tr>
							<td><a href="#"><img src="images/logo.png" alt="zlinepay"
									width="150" height="45" border="0" /></a></td>
							<td style="text-align: right;"><span style="color: #868B94;">感谢您使用支付平台</span></td>
						</tr>
						<tr>
						</tr>
						<tr>
							<td colspan="2"
								style="color: #fff; font-size: 14px; height: 40px; background: #2C69C1;">支付产品通用支付接口演示</td>
						</tr>
			<?php
$outOrderId = "";
for ($i = 0; $i < 32; $i ++) {
    $outOrderId .= rand(0, 9);
}
?>
			
			<tr>
							<td>商户订单号</td>
							<td>&nbsp;&nbsp;<input size="50" type="text" name="outOrderId"
								id="outOrderId" value="<?php
        echo ($outOrderId)?>" /> &nbsp;<span
								style="color: #FF0000; font-weight: 100;">*</span></td>
						</tr>
						<tr>
							<td>支付金额(分)</td>
							<td>&nbsp;&nbsp;<input size="50" type="text" name="totalAmount"
								id="totalAmount" value="1" />&nbsp;<span
								style="color: #FF0000; font-weight: 100;">*</span></td>
						</tr>
						<tr>
							<td>商品名称</td>
							<td>&nbsp;&nbsp;<input size="50" type="text" name="goodsName"
								id="goodsName" value="productname" /></td>
						</tr>
						<tr>
							<td>商品描述</td>
							<td>&nbsp;&nbsp;<input size="50" type="text" name="goodsExplain"
								id="goodsExplain" value="goodsExplain" /></td>
						</tr>
						<tr>
							<td>扩展字段</td>
							<td>&nbsp;&nbsp;<input size="50" type="text" name="goodsExplain"
								id="ext" value="ext" /></td>
						</tr>
						<tr>
							<td>商户取货地址</td>
							<td>&nbsp;&nbsp;<input size="50" type="text" name="merUrl"
								id="merUrl" value="http://192.168.13.160/EHK_PHP/rec.php" />&nbsp;<span
								style="color: #FF0000; font-weight: 100;">*</span></td>
						</tr>
						<tr>
							<td>通知商户服务端地址</td>
							<td>&nbsp;&nbsp;<input size="50" type="text" name="noticeUrl"
								id="noticeUrl" value="http://192.168.13.160/EHK_PHP/notice.php" />&nbsp;<span
								style="color: #FF0000; font-weight: 100;">*</span></td>
						</tr>
						<input type="hidden" name="bankCardType" id="bankCardType"
							value="" />
						<tr>
							<td style="vertical-align: sub;">支付方式</td>
							<td>
								<div id="tabbox">
									<ul class="tabs" id="tabs">
										<li><a href="#" tab="tab1">个人网银</a></li>
										<li><a href="#" tab="tab2">企业网银</a></li>
										<li><a href="#" tab="tab3">个人借记卡</a></li>
									</ul>
									<ul class="tab_conbox">
										<li id="tab1" class="tab_con">
											<div style="margin-bottom: 20px;">
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="BOC" /> <img src="images/perBank/BOC.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="ABC" /> <img src="images/perBank/ABC.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="ICBC" /> <img src="images/perBank/ICBC.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CCB" /> <img src="images/perBank/CCB.gif" />
												</div>
											</div>
											<div style="margin-bottom: 20px;">
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="BCM" /> <img src="images/perBank/BCM.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CMB" /> <img src="images/perBank/CMB.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CEB" /> <img src="images/perBank/CEB.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="SPDB" /> <img src="images/perBank/SPDB.gif" />
												</div>
											</div>
											<div style="margin-bottom: 20px;">
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="BCCB" /> <img src="images/perBank/BCCB.gif"
														style="padding-right: 19px;" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="PSBC" /> <img src="images/perBank/PSBC.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="BOS" /> <img src="images/perBank/BOS.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CIB" /> <img src="images/perBank/CIB.gif" />
												</div>
											</div>
											<div style="margin-bottom: 20px;">
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CITIC" /> <img src="images/perBank/CITIC.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CMBC" /> <img src="images/perBank/CMBC.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="GDB" /> <img src="images/perBank/GDB.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="HXB" /> <img src="images/perBank/HXB.gif" />
												</div>
											</div>
											<div style="margin-bottom: 10px;">
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="PAB" /> <img src="images/perBank/PAB.gif"
														style="padding-right: 18px;" />
												</div>
											</div>
										</li>

										<li id="tab2" class="tab_con">
											<div style="margin-bottom: 20px;">
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="BOC" /> <img src="images/corBank/BOC.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="ICBC" /> <img src="images/corBank/ICBC.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CCB" /> <img src="images/corBank/CCB.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CMB" /> <img src="images/corBank/CMB.gif" />
												</div>
											</div>
											<div style="margin-bottom: 10px;">
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CEB" /> <img src="images/corBank/CEB.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="SPDB" /> <img src="images/corBank/SPDB.gif" />
												</div>
											</div>
										</li>
										<li id="tab3" class="tab_con">
											<div style="margin-bottom: 20px;">
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="BOC" /> <img src="images/perBank/BOC.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="ABC" /> <img src="images/perBank/ABC.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="ICBC" /> <img src="images/perBank/ICBC.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CCB" /> <img src="images/perBank/CCB.gif" />
												</div>
											</div>
											<div style="margin-bottom: 20px;">
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="BCM" /> <img src="images/perBank/BCM.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CMB" /> <img src="images/perBank/CMB.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CEB" /> <img src="images/perBank/CEB.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="SPDB" /> <img src="images/perBank/SPDB.gif" />
												</div>

											</div>
											<div style="margin-bottom: 20px;">
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="BCCB" /> <img src="images/perBank/BCCB.gif"
														style="padding-right: 19px;" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="BJRCB" /> <img src="images/perBank/BJRCB.gif"
														style="padding-right: 19px;" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="BOS" /> <img src="images/perBank/BOS.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CIB" /> <img src="images/perBank/CIB.gif" />
												</div>
											</div>
											<div style="margin-bottom: 20px;">
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CITIC" /> <img src="images/perBank/CITIC.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="CMBC" /> <img src="images/perBank/CMBC.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="GDB" /> <img src="images/perBank/GDB.gif" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="HXB" /> <img src="images/perBank/HXB.gif" />
												</div>
											</div>
											<div style="margin-bottom: 10px;">
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="PAB" /> <img src="images/perBank/PAB.gif"
														style="padding-right: 18px;" />
												</div>
												<div class="ra-img">
													<input type="radio" name="bankCode" id="bankCode"
														value="PSBC" /> <img src="images/perBank/PSBC.gif" />
												</div>
											</div>
										</li>
									</ul>
								</div>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;&nbsp;<input type="submit" value="马上支付" id="pay" /></td>
						</tr>
					</table>
				</form>
			</td>
		</tr>
	</table>

	<script type="text/javascript">
$(document).ready(function() {
    jQuery.jqtab = function(tabtit,tabcon) {
        $(tabcon).hide();
        $(tabtit+" li:first").addClass("thistab").show();
        $(tabcon+":first").show();
        $("#bankCardType").val("00");
        $(tabtit+" li").click(function() {
            $(tabtit+" li").removeClass("thistab");
            $(this).addClass("thistab");
            $(tabcon).hide();
            var activeTab = $(this).find("a").attr("tab");
            $("#"+activeTab).fadeIn();
            if(activeTab=="tab1") {$("#bankCardType").val("00");$("#pay").attr("disabled","disabled");$('.tab_conbox :radio').attr("checked",false);}//根据支付银行类型00个人综合03企业
            if(activeTab=="tab2") {$("#bankCardType").val("03");$("#pay").attr("disabled","disabled");$('.tab_conbox :radio').attr("checked",false);}
            if(activeTab=="tab3") {$("#bankCardType").val("01");$("#pay").attr("disabled","disabled");$('.tab_conbox :radio').attr("checked",false);}            
return false;
        });
        
    };
//    /*调用方法如下：*/
    $.jqtab("#tabs",".tab_con");
    
	$('.tab_conbox :radio').attr("checked",false);   //默认不点中
	$(':radio').click(function(){
		var raVal = $(this).attr("checked");
		if(raVal==true){
			$(this).parent().siblings().children(":radio").attr("checked",false)
			       .parent().parent().siblings().children().children(":radio").attr("checked",false);
			$("#pay").removeAttr("disabled");
			
		}
		//$("#bankCode").val($(this).val());//设置文本框银行编码
	});
	
	if(!($(":radio").is(':checked'))){
		$("#pay").attr("disabled","disabled");		
	}
	
	if((isFirefox=navigator.userAgent.indexOf("Firefox")>0) || (isIE = navigator.userAgent.indexOf("MSIE")>0) || (Object.hasOwnProperty.call(window, "ActiveXObject") && !window.ActiveXObject)){  
		$('.tabs').css({"margin-bottom":"-17px"});
	}
});
</script>
</body>
</html>