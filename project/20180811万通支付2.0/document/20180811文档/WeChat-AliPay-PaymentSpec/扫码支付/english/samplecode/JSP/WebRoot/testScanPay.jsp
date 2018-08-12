<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.util.*" %>
<%@ page import="java.text.SimpleDateFormat" %>

<html>
  <head>
  	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script type="text/javascript" src="./js/jquery-1.8.0.js"></script>
	<script type="text/javascript" src="./js/jquery.qrcode.js"></script>
	<script type="text/javascript" src="./js/utf.js"></script>
	<script>

function sQrcode(qdata){

	$("#showqrcode").empty().qrcode({		// create QRcode image
			render : "canvas",    			// render method
			text : qdata,    				// qrcode data
			width : "200",              	// width
			height : "200",             	// height
			background : "#ffffff",     	// background color
			foreground : "#000000",     	// foreground color
			src: ""    						// logo image
		});	
		
}	
	
$(document).ready(function(){

	jQuery( function($){
        var url = "http://chaxun.1616.net/s.php?type=ip&output=json&callback=?&_=" + Math.random();
        $.getJSON(url, function(data){
            document.getElementById("client_ip").value = data.Ip;  // get client_ip
        });
	});

	$("#submit").click(function(){
		
 		var formParam = $("#ScanPay").serialize();		// organization data  
		alert("The data of commit is " + formParam);

		$.ajax({  										// jQuery Ajax asynchronous commit data
			type:"post",      							// commit type
         	url:"./ScanPay.jsp",  						// url address	  						
         	data:formParam,   							// the data to commit
         	dataType:"text",  							// return data type
         	success:function(data,textStatus){			// callback successed
         	
         	 			$("#xmldata").text(data);        	 		
         	 			var resp_code = $(data).find("resp_code").text();
         	 			var result_code = $(data).find("result_code").text();
         	 			if( resp_code == "SUCCESS" && result_code == "0" ){         	 		
         	 				var qrcode = $(data).find("qrcode").text();
         	 				sQrcode(qrcode);
         	 			}else if ( resp_code == "SUCCESS" && result_code == "1"  ){
         	 				$("#showqrcode").text("order-is-already-exist!");
         	 				document.getElementById("showqrcode").style.color="red";
							document.getElementById("showqrcode").style.fontSize="200%";
         	 			}  
         	 			      	 		
     				},
         	error:function(){       					// callback failed
         			    $("#xmldata").text("Return reslut failed!");         			
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
  				<form id="ScanPay">
					<div>merchant_code：<input Type="text" Name="merchant_code" id="merchant_code" value="1111110166"> * </div>
					<div>service_type：
						<select name="service_type" id="service_type">
							<option value="weixin_scan">weixin_scan</option>
							<option value="alipay_scan">alipay_scan</option>
							<option value="qq_scan">qq_scan</option>
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
  			<td><div id="showqrcode"><img src=""></div></td>
		</tr>
		<tr>
			<td colspan="2">
				<div>Show Return Result：</div>
				<textarea rows="12" cols="90" id="xmldata"></textarea>
			</td>
		</tr>
	</table>
  </body>
</html>
