<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="selPayType.aspx.cs" Inherits="mustpay.selPayType" %>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
   	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>MustPay统一下单选择支付方式页</title>
        <style type="text/css">
		*{margin:0;padding:0;font-family: Arial,microsoft yahei,"微软雅黑";}
		.header{height:26px;line-height:26px;background:#8F8F8F;color:#FFF;}
		.header p{width:1200px;margin:0 auto;text-align:right;font-size:14px;}
		.logo-box{margin:20px auto;width:1200px;}
		.logo-box span{height:36px;line-height:36px;color:#666;font-size:18px;padding-left:20px;}
		.pay-box{margin:30px auto;width:1200px;background:#F8F8F8;border-radius:20px;}
		.pay-box .goods-name{padding:30px 0;color:#232323;text-align:center;font-size:22px;}
		.pay-box .goods-price{padding:30px 0;color:#FF6600;text-align:center;font-size:22px;}
		.pay-box .type-box{margin-top:10px;float:left;width:80%;margin-left:10%;}
		.pay-box .type-box .type-con{float:left;width:180px;margin:50px 0 0 105px;height:40px;line-height:38px;border:1px solid #CCC;text-align:center;background:#FFF;cursor:pointer;}
		.pay-box .type-box .sel-con{border:1px solid #52e2c6;box-shadow: 0px 0px 12px rgba(82, 226, 198, 0.8);}
		.pay-box .type-box .type-con img{vertical-align: middle;}
		.pay-box .type-box .type-con span{vertical-align: middle;color:#232323;}
		.pay-box .sub-btn{width:180px;height:40px;line-height:40px;color:#FFF;margin:200px 0 60px 860px;float:left;background:#52e2c6;text-align:center;cursor:pointer;}
	</style>

</head>
<body>
		<div class="header">
			<p>你好，欢迎使用科诺支付</p>
		</div>
		
		<div class="logo-box">
			<img src="http://pingtai.chinaxiangqiu.com:8098/testpay/images/logo.png" height="36px">
			<span>科诺支付测试Demo</span>
		</div>
		
		<div class="pay-box">
			<div class="goods-name">
				<%= subject%>
			</div>
			<div class="goods-price">
				<%= total_fee%>元
			</div>
		<%--	<input type="hidden" id="prepayId" value="<%= GetPrepayId()%>">--%>
			<div class="type-box">
				<div class="type-con sel-con" data-type="ali_pay_pc">
					<img src="http://pingtai.chinaxiangqiu.com:8098/testpay/images/zhifubao.png" height="25px">
					<span>mustPay支付</span>
				</div>
				<div class="type-con" data-type="wx_pay_pc">
					<img src="http://pingtai.chinaxiangqiu.com:8098/testpay/images/weixin.png" height="25px">
					<span>微信支付</span>
				</div>
				<div class="type-con" data-type="union_pay_pc">
					<img src="http://pingtai.chinaxiangqiu.com:8098/testpay/images/yinlian.png" height="25px">
					<span>银联支付</span>
				</div>
				<div class="type-con" data-type="bd_pay_pc">
					<img src="http://pingtai.chinaxiangqiu.com:8098/testpay/images/baidu.png" height="25px">
					<span>百度支付</span>
				</div>
				<div class="type-con" data-type="jd_pay_pc">
					<img src="http://pingtai.chinaxiangqiu.com:8098/testpay/images/jingdong.png" height="25px">
					<span>京东支付</span>
				</div>
				<div class="type-con" data-type="wy_pay_pc">
					<img src="http://pingtai.chinaxiangqiu.com:8098/testpay/images/wangyi.jpg" height="25px">
					<span>网易支付</span>
				</div>
			</div>
			<div class="sub-btn" onclick="goPay();">去支付</div>
			<div style="clear:both;"></div>
		</div>
<%--		    <script src="http://cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>--%>
    <script src="Scripts/jquery-1.4.1.min.js" type="text/javascript"></script>
		<script src="https://service.chinaxiangqiu.com/service/js/pcsdk.js"></script>
		<script type="text/javascript">
		    var prepayID = "<%= GetPrepayId()%>";
		    $('.type-box .type-con').click(function () {
		        $('.type-box .type-con').removeClass('sel-con');
		        $(this).addClass('sel-con');
		    });
		    //选择支付方式
		    function goPay() {
		        var type = $('.sel-con').attr('data-type');
		        MUSTPAY.init({
		            'apps_id': '<%= apps_id%>', //科诺支付系统分配的应用ID号
		            'prepay_id': prepayID, //商户通过统一下单接口获取的预支付ID
		            'pay_type': type //开通的通道简称
		        });
		    }
		</script>
	</body>
</html>
