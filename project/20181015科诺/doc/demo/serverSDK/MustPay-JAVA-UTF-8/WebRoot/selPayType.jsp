<%
/* *
 *功能：科诺支付统一下单选择支付方式页
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 *该代码仅供学习和研究科诺支付接口使用，只是提供一个参考。
 */
%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="com.科诺支付.config.*"%>
<%@ page import="com.科诺支付.util.*"%>
<%@ page import="java.util.HashMap"%>
<%@ page import="java.util.Map"%>
<%@ page import="java.math.BigDecimal"%>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>科诺支付统一下单选择支付方式页</title>
	</head>
	<%
		////////////////////////////////////请求参数//////////////////////////////////////

        //商户订单号，商户网站订单系统中唯一订单号，必填
        String out_trade_no = new String(request.getParameter("orderId").getBytes("ISO-8859-1"),"UTF-8");
		
        //商品名称，必填
        String subject = new String(request.getParameter("goodsName").getBytes("ISO-8859-1"),"UTF-8");
		
        //付款金额，必填（单位：分）
        String total_fee = new String(request.getParameter("price").getBytes("ISO-8859-1"),"UTF-8");
		
        //商品展示的超链接，选填
        String show_url = new String(request.getParameter("showUrl").getBytes("ISO-8859-1"),"UTF-8");
		
        //商品描述，选填
        String body = new String(request.getParameter("goodsDesc").getBytes("ISO-8859-1"),"UTF-8");
		
		//////////////////////////////////////////////////////////////////////////////////
		
		//把请求参数打包成数组并签名
		Map<String, String> sParaTemp = new HashMap<String, String>();
		sParaTemp.put("apps_id", 科诺支付Config.APPS_ID);
		sParaTemp.put("out_trade_no", out_trade_no);
        sParaTemp.put("mer_id", 科诺支付Config.MER_ID);
		sParaTemp.put("total_fee", String.valueOf(new BigDecimal(total_fee).multiply(new BigDecimal(100)).intValue()));//将元转换为分
		sParaTemp.put("subject", subject);
		sParaTemp.put("body", body);
		sParaTemp.put("notify_url", 科诺支付Config.NOTIFY_URL);
		sParaTemp.put("return_url", 科诺支付Config.RETURN_URL);
		sParaTemp.put("show_url", show_url);
		sParaTemp.put("user_id", "15888881234");//用户在商户平台的唯一标示
		sParaTemp.put("extra", "123456");//扩展字段，在异步通知时会原样返回给商户
		
		//将请求参数进行RSA签名
		sParaTemp = 科诺支付Core.buildRequestPara(sParaTemp);

		//请求科诺支付获取预支付ID
		String prepayId = 科诺支付Request.requestPrepayId(sParaTemp);
		
	%>
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
			<input type="hidden" id="prepayId" value="<%= prepayId%>">
			<div class="type-box">
				<div class="type-con sel-con" data-type="ali_pay_pc">
					<img src="http://pingtai.chinaxiangqiu.com:8098/testpay/images/zhifubao.png" height="25px">
					<span>支付宝支付</span>
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
		<script src="https://cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://service.kenuolife.com/service/js/pcsdk.js"></script>
		<script>
			$('.type-box .type-con').click(function(){
				$('.type-box .type-con').removeClass('sel-con');
				$(this).addClass('sel-con');
			});
			//选择支付方式
			function goPay(){
				var type = $('.sel-con').attr('data-type');
				MUSTPAY.init({
					'apps_id': '<%= MUSTPAYConfig.APPS_ID%>', //科诺支付系统分配的应用ID号
		            'prepay_id': '<%= prepayId%>', //商户通过统一下单接口获取的预支付ID
		            'pay_type': type //开通的通道简称
				});
			}
		</script>
	</body>
</html>
