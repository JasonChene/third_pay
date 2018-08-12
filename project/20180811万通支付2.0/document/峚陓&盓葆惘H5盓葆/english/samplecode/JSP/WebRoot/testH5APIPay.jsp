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
            document.getElementById("client_ip").value = data.Ip;  // get client_ip
        });
	});

	$("#submit").click(function(){
		
 		var formParam = $("#H5APIPay").serialize();		// organization data  
		alert("提交的表单参数数据：" + formParam);

		$.ajax({  										// jQuery Ajax asynchronous commit data
			type:"post",      							// commit type
         	url:"./H5APIPay.jsp",  						// url address	  					
         	data:formParam,   							// the data to commit
         	dataType:"text",  							// return data type
         	success:function(data,textStatus){			// callback successed
         	
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
         	error:function(){       					// callback failed
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
					<div>merchant_code：<input Type="text" Name="merchant_code" id="merchant_code" value="1111110166"> * </div>
					<div>service_type：
						<select name="service_type" id="service_type">
							<option value="alipay_h5api">alipay_h5api</option>
							<option value="weixin_h5api">weixin_h5api</option>
						</select> *	</div>												
					<div>notify_url：<input Type="text" Name="notify_url" id="notify_url" value="http://zhangl.imwork.net:58812/ScanPay_Demo/Notify_Url.jsp"> * </div>				
					<div>interface_version：<input Type="text" Name="interface_version" id="interface_version" value="V3.1"/> * </div>	
					<div>client_ip：<input Type="text" Name="client_ip" id="client_ip" value="120.237.123.242"/> * </div>																	
					<div>sign_type：
						<select name="sign_type" id="sign_type">
							<option value="RSA-S">RSA-S</option>
							<option value="RSA">RSA</option>
						</select> *	</div>																	
					<div>order_no：<input Type="text" Name="order_no" id="order_no" value="<%=new SimpleDateFormat("yyyyMMddHHmmss").format(new Date())%>"> * </div>
					<div>order_time：<input Type="text" Name="order_time" id="order_time" value="<%=new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format(new Date())%>"/>* </div>		
					<div>order_amount：<input Type="text" Name="order_amount" id="order_amount" value="0.01"> * </div>		
					<div>product_name：<input Type="text" Name="product_name" id="product_name" value="iPhone"> * 	</div>			
				</form>
				<button id="submit">submit</button> 			
  			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div>Show Return Result：</div>
				<textarea rows="12" cols="90" id="xmldata"></textarea>
			</td>
		</tr>
		<tr>
			<td>Show Return Pay URL：<div id="payURL"></div></td>
		</tr>
		<tr>
			<td>Show Return Pay URL（URLEncode）:<div id="payURLEncode"></div></td>
		</tr>
	</table>
  </body>
</html>
