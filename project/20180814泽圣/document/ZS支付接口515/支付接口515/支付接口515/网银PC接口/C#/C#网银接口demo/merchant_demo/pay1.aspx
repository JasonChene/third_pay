<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="pay1.aspx.cs" Inherits="merchant_demo.pay1" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>泽圣支付</title>
<meta name="keywords"content="" />
		<link href="css/yeepaytest.css" type="text/css" rel="stylesheet" />
    <script src="jquery.min.js" type="text/javascript"></script>
	</head>
	<body>
      <table width="60%" border="0" align="center" cellpadding="0"
		cellspacing="0" style="border: solid 1px #40506b;">
		<tr>
			<td>
				<form action="PayDo1.aspx" method="post">
				<table width="100%" border="0" align="center" cellpadding="5"
					cellspacing="1" style="border-spacing: 0;">
					<tr>
						<td><a href="#"><img
								src="images/logo.png" alt="泽圣支付" width="150"
								height="45" border="0" /></a></td>
						<td style="text-align: right;"><span style="color: #868B94;">感谢您使用支付平台</span></td>
					</tr>
					<tr>
						<td colspan="2"
							style="color: #fff; font-size: 14px; height: 40px; background: #2C69C1;">支付产品通用支付接口演示</td>
					</tr>
					<tr>
						<td>商户订单号</td>
						<td>&nbsp;&nbsp;<input size="50" type="text"
							name="outOrderId" id="Text1" value="123123321123" />&nbsp;<span
							style="color: #FF0000; font-weight: 100;">*</span></td>
					</tr>
					<tr>
						<td>支付金额(分)</td>
						<td>&nbsp;&nbsp;<input size="50" type="text"
							name="totalAmount" id="Text2" value="1" />&nbsp;<span
							style="color: #FF0000; font-weight: 100;">*</span></td>
					</tr>
					<tr>
						<td>商品名称</td>
						<td>&nbsp;&nbsp;<input size="50" type="text"
							name="goodsName" id="Text3" value="goodsName" /></td>
					</tr>
					<tr>
						<td>商品描述</td>
						<td>&nbsp;&nbsp;<input size="50" type="text"
							name="goodsExplain" id="Text4" value="goodsExplain" /></td>
					</tr>
                    <tr>
						<td>扩展字段</td>
						<td>&nbsp;&nbsp;<input size="50" type="text"
							name="ext" id="Text11" value="ext" /></td>
					</tr>
					<tr>
						<td>商户取货地址</td>
						<td>&nbsp;&nbsp;<input size="50" type="text" name="merUrl"
							id="Text5" value="<%=Demo.Class.ProperConst.merUrl%>" />&nbsp;<span
							style="color: #FF0000; font-weight: 100;">*</span></td>
					</tr>
					<tr>
						<td>通知商户服务端地址</td>
						<td>&nbsp;&nbsp;<input size="50" type="text"
							name="noticeUrl" id="Text6"
							value="<%=Demo.Class.ProperConst.noticeUrl%>" />&nbsp;<span
							style="color: #FF0000; font-weight: 100;">*</span></td>
					</tr>
						<tr style="display:none;">
						<td>支付银行卡类型</td>
						<td>&nbsp;&nbsp;<input size="50" type="text" name="bankCardType"
							id="bankCardType" value="00" /></td>
					</tr>
					<tr style="display:none;">
						<td>支持银行编码</td>
						<td>&nbsp;&nbsp;<input size="50" type="text" name="bankCode" id="bankCode" value="" /></td>
					</tr>
				<!--
			<tr>
				<td>支付银行卡类型</td>
				<td>&nbsp;&nbsp;<select name="bankCardType" id="bankCardType"
					style="height: 34px; width: 200px;">
					<option value="00">B2C借贷记综合</option>
					<option value="01" selected="selected">B2C纯借记</option>
					<option value="03">B2B企业网银</option>
				</select> 01-B2C纯借记,00-B2C借贷记综合,03-B2B企业网银</td>
			</tr>
			<tr>
				<td>支持银行编码</td>
				<td>&nbsp;&nbsp;<select name='bankCode'
					style="height: 34px; width: 200px;">
					<option value="BOS">上海银行</option>
					<option value="ICBC">工商银行</option>
					<option value="CMBC">招商银行</option>
					<option value="ABC">中国农业银行</option>
					<option value="CCB">建设银行</option>
					<option value="BCCB">北京银行</option>
					<option value="PAB">平安银行</option>
				</select></td>
			</tr>-->
			<tr>
				<td style="vertical-align: sub;">支付方式</td>
				<td>
					<div id="tabbox">
						<ul class="tabs" id="tabs">
						   <li><a href="#" tab="tab1">个人信用卡</a></li>
						   <li><a href="#" tab="tab2">企业网银</a></li>
						     <li><a href="#" tab="tab3">个人借记卡</a></li>
						</ul>
						<ul class="tab_conbox">
							<li id="tab1" class="tab_con">
								<div style="margin-bottom:20px;">
									<div class="ra-img">
									   <input type="radio" name="" id="" value="BOC">
									   <img src="images/perBank/BOC.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="ABC">
									   <img src="images/perBank/ABC.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="ICBC">
									   <img src="images/perBank/ICBC.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CCB">
									   <img src="images/perBank/CCB.gif" />
								   </div>
							   </div>
							   <div style="margin-bottom:20px;">
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="BCM">
									   <img src="images/perBank/BCM.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CMB">
									   <img src="images/perBank/CMB.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CEB">
									   <img src="images/perBank/CEB.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="SPDB">
									   <img src="images/perBank/SPDB.gif" />
								   </div>
							   </div>
							   <div style="margin-bottom:20px;">
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="BCCB">
									   <img src="images/perBank/BCCB.gif" style="padding-right: 19px;"/>
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="PSBC">
									   <img src="images/perBank/PSBC.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="BOS">
									   <img src="images/perBank/BOS.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CIB">
									   <img src="images/perBank/CIB.gif" />
								   </div>
							   </div>
							   <div style="margin-bottom:20px;">
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CITIC">
									   <img src="images/perBank/CITIC.gif" /> 
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CMBC">
									   <img src="images/perBank/CMBC.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="GDB">
									   <img src="images/perBank/GDB.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="HXB">
									   <img src="images/perBank/HXB.gif" />
								   </div>
							   </div>
							   <div style="margin-bottom:10px;">
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="PAB">
									   <img src="images/perBank/PAB.gif" style="padding-right: 18px;" />
								   </div>
							   </div>
							</li>
								
							<li id="tab2" class="tab_con">
								<div style="margin-bottom:20px;">
									<div class="ra-img">
									   <input type="radio" name="" id="" value="BOC">
									   <img src="images/corBank/BOC.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="ICBC">
									   <img src="images/corBank/ICBC.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CCB">
									   <img src="images/corBank/CCB.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CMB">
									   <img src="images/corBank/CMB.gif" />
								   </div>
							   </div>
							   <div style="margin-bottom:10px;">
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CEB">
									   <img src="images/corBank/CEB.gif"/>
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="SPDB">
									   <img src="images/corBank/SPDB.gif" />
								   </div>
							   </div>
							</li>
					<li id="tab3" class="tab_con">
								<div style="margin-bottom:20px;">
									<div class="ra-img">
									   <input type="radio" name="" id="" value="BOC">
									   <img src="images/perBank/BOC.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="ABC">
									   <img src="images/perBank/ABC.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="ICBC">
									   <img src="images/perBank/ICBC.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CCB">
									   <img src="images/perBank/CCB.gif" />
								   </div>
							   </div>
							   <div style="margin-bottom:20px;">
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="BCM">
									   <img src="images/perBank/BCM.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CMB">
									   <img src="images/perBank/CMB.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CEB">
									   <img src="images/perBank/CEB.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="SPDB">
									   <img src="images/perBank/SPDB.gif" />
								   </div>
							   </div>
							   <div style="margin-bottom:20px;">
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="BCCB">
									   <img src="images/perBank/BCCB.gif" style="padding-right: 19px;"/>
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="PSBC">
									   <img src="images/perBank/PSBC.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="BOS">
									   <img src="images/perBank/BOS.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CIB">
									   <img src="images/perBank/CIB.gif" />
								   </div>
							   </div>
							   <div style="margin-bottom:20px;">
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CITIC">
									   <img src="images/perBank/CITIC.gif" /> 
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="CMBC">
									   <img src="images/perBank/CMBC.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="GDB">
									   <img src="images/perBank/GDB.gif" />
								   </div>
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="HXB">
									   <img src="images/perBank/HXB.gif" />
								   </div>
							   </div>
							   <div style="margin-bottom:10px;">
								   <div class="ra-img">
									   <input type="radio" name="" id="" value="PAB">
									   <img src="images/perBank/PAB.gif" style="padding-right: 18px;" />
								   </div>
							   </div>
							</li>
						</ul>    
					</div>
				</td>
			</tr>

					<tr>
						<td>订单生成时间</td>
						<td>&nbsp;&nbsp;<input size="50" type="text"
							name="orderCreateTime" id="orderCreateTime" value="20150210213410" /></td>
					</tr>
					<tr>
						<td >&nbsp;</td>
		  				<td >&nbsp;&nbsp;<input type="submit" value="马上支付" id="pay"/></td>
					</tr>
				</table>
				</form>
			</td>
		</tr>
	</table>
	<script type="text/javascript">
	    $(document).ready(function () {
	        jQuery.jqtab = function (tabtit, tabcon) {
	            $(tabcon).hide();
	            $(tabtit + " li:first").addClass("thistab").show();
	            $(tabcon + ":first").show();

	            $(tabtit + " li").click(function () {
	                $(tabtit + " li").removeClass("thistab");
	                $(this).addClass("thistab");
	                $(tabcon).hide();
	                var activeTab = $(this).find("a").attr("tab");
	                $("#" + activeTab).fadeIn();
	                if (activeTab == "tab1") $("#bankCardType").val("00");//根据支付银行类型00个人综合03企业
	                if (activeTab == "tab2") $("#bankCardType").val("03");
	                if (activeTab == "tab3") $("#bankCardType").val("01");
	                return false;
	            });

	        };
	        //    /*调用方法如下：*/
	        $.jqtab("#tabs", ".tab_con");

	        $('.tab_conbox :radio').attr("checked", false);   //默认不点中
	        $(':radio').click(function () {
	            var raVal = $(this).attr("checked");
	            if (raVal == true) {
	                $(this).parent().siblings().children(":radio").attr("checked", false)
                           .parent().parent().siblings().children().children(":radio").attr("checked", false);
	                $("#pay").removeAttr("disabled");
	                $("#bankCode").val($(this).val());//设置文本框银行编码
	            }
	        });

	        if (!($(":radio").is(':checked'))) {
	            $("#pay").attr("disabled", "disabled");
	        }

	        if ((isFirefox = navigator.userAgent.indexOf("Firefox") > 0) || (isIE = navigator.userAgent.indexOf("MSIE") > 0) || (Object.hasOwnProperty.call(window, "ActiveXObject") && !window.ActiveXObject)) {
	            $('.tabs').css({ "margin-bottom": "-17px" });
	        }
	    });


</script>

	</body>
</html>