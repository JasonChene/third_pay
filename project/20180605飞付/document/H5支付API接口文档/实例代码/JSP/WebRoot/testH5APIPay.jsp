<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.util.*" %>
<%@ page import="java.text.SimpleDateFormat" %>

<html>
  <head>
  	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script type="text/javascript" src="./js/jquery-1.8.0.js"></script>
	<script type="text/javascript" src="./js/utf.js"></script>
	<script>

$(document).ready(function(){

	jQuery( function($){
        var url = "http://chaxun.1616.net/s.php?type=ip&output=json&callback=?&_=" + Math.random();
        $.getJSON(url, function(data){
            document.getElementById("client_ip").value = data.Ip;  // 获取客户端IP
        });
	});

	$("#submit").click(function(){
		
 		var formParam = $("#H5APIPay").serialize();		// 序列化表单内容为字符串  
		alert("提交的表单参数数据：" + formParam);

		$.ajax({  										// jQuery Ajax 异步提交数据
			type:"post",      							// 数据提交方式
         	url:"./H5APIPay.jsp",  						// 提交的url地址  						
         	data:formParam,   							// 提交的数据
         	dataType:"text",  							// 返回的数据类型
         	success:function(data,textStatus){			// 回调成功
         	
         	 			$("#xmldata").text(data);        	 		
         	 			var resp_code = $(data).find("resp_code").text();
         	 			var result_code = $(data).find("result_code").text();
         	 			if( resp_code == "SUCCESS" && result_code == "0" ){         	 		
         	 				var payURL = $(data).find("payURL").text();
         	 				$("#payURL").text(payURL);
         	 				$("#payURLEncode").text(decodeURIComponent(payURL));
         	 			}else if ( resp_code == "SUCCESS" && result_code == "1"  ){
         	 				$("#payURL").text("订单已存在!");
         	 			}  
         	 			      	 		
     				},
         	error:function(){       					// 回调失败
         			    $("#xmldata").text("程序异常，XML数据返回失败!");         			
         			}
    	});      		
	});
});
	
	</script>
  </head>
  
  <body>
	<table>
		<tr>
  			<td>
  				<form id="H5APIPay">
					<div>商 家 号(merchant_code)：<input Type="text" Name="merchant_code" id="merchant_code" value="800004007888"> * </div>
					<div>业务类型(service_type)：
						<select name="service_type" id="service_type">
							<option value="alipay_h5api">alipay_h5api</option>
							<option value="weixin_h5api">weixin_h5api</option>
							<option value="qq_h5api">qq_h5api</option>
						</select> *	</div>												
					<div>服务器异步通知地址(notify_url)：<input Type="text" Name="notify_url" id="notify_url" value="http://zhangl.imwork.net:58812/ScanPay_Demo/Notify_Url.jsp"> * </div>				
					<div>接口版本(interface_version)：<input Type="text" Name="interface_version" id="interface_version" value="V3.1"/> * </div>	
					<div>客户端IP(client_ip)：<input Type="text" Name="client_ip" id="client_ip"/> * </div>																	
					<div>签名方式(sign_type)：
						<select name="sign_type" id="sign_type">
							<option value="RSA-S">RSA-S</option>
							<option value="RSA">RSA</option>
						</select> *	</div>																	
					<div>商户订单号(order_no)：<input Type="text" Name="order_no" id="order_no" value="<%=new SimpleDateFormat("yyyyMMddHHmmss").format(new Date())%>"> * </div>
					<div>商户订单时间(order_time)：<input Type="text" Name="order_time" id="order_time" value="<%=new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format(new Date())%>"/>* </div>		
					<div>商户订单金额(order_amount)：<input Type="text" Name="order_amount" id="order_amount" value="0.01"> * </div>		
					<div>商品名称(product_name)：<input Type="text" Name="product_name" id="product_name" value="iPhone"> * 	</div>			
				</form>
				<button id="submit">提交支付参数</button> 			
  			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div>返回的XML数据：</div>
				<textarea rows="12" cols="90" id="xmldata"></textarea>
			</td>
		</tr>
		<tr>
			<td>返回支付地址:<div id="payURL"></div></td>
		</tr>
		<tr>
			<td>返回支付地址（URLEncode）:<div id="payURLEncode"></div></td>
		</tr>
	</table>
  </body>
</html>
