<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.util.*" %>

<html>
  <head>    
  	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script type="text/javascript" src="./js/jquery-1.8.0.js"></script>
	<script>
	
$(document).ready(function(){

	$("#submit").click(function(){
		
 		var formParam = $("#queryords").serialize();		// 序列化表单内容为字符串  
		alert("提交的表单参数数据：" + formParam);

		$.ajax({  											// jQuery Ajax 异步提交数据
			type:"post",      								// 数据提交方式
         	url:"./QueryOrds.jsp",  						// 提交的url地址
         	data:formParam,   								// 提交的数据
         	dataType:"text",  								// 返回的数据类型
         	success:function(data,textStatus){				// 回调成功         	
         	 			$("#xmldata").text(data);        	 		         	 			      	 		
     				},
         	error:function(){       						// 回调失败
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
				<form id="queryords">
					<div>商 家 号(merchant_code)：<input Type="text" Name="merchant_code" id="merchant_code" value="200001001001"> * </div>
					<div>服务类型(service_type)：<input Type="text" Name="service_type" id="service_type" value="single_trade_query"> * </div>
					<div>接口版本(interface_version)：<input Type="text" Name="interface_version" id="interface_version" value="V3.0"/> * </div>					
					<div>签名方式(sign_type)：
						<select name="sign_type" id="sign_type">
							<option value="RSA-S">RSA-S</option>
							<option value="RSA">RSA</option>
						</select> *	</div>		
					<div>商户订单号(order_no)：<input Type="text" Name="order_no" id="order_no" > * </div>
					<div>订单号(trade_no)：<input Type="text" Name="trade_no" id="trade_no" > </div>																																		
				</form>
				<button id="submit">提交查询参数</button> 
			</td>
		</tr>
		<tr>
			<td>
				<div>查询返回的XML数据：</div>
				<textarea rows="12" cols="90" id="xmldata"></textarea>
			</td>
		</tr>
	</table>
  </body>
</html>