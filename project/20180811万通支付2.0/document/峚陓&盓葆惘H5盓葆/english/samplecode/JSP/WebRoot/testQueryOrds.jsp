<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.util.*" %>

<html>
  <head>    
  	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script type="text/javascript" src="./js/jquery-1.8.0.js"></script>
	<script>
	
$(document).ready(function(){

	$("#submit").click(function(){
		
 		var formParam = $("#queryords").serialize();		// organization data 
		alert("The data of commit is " + formParam);

		$.ajax({  											// jQuery Ajax asynchronous commit data
			type:"post",      								// commit type
         	url:"./QueryOrds.jsp",  						// url address
         	data:formParam,   								// the data to commit
         	dataType:"text",  								// return data type
         	success:function(data,textStatus){				// callback successed      	
         	 			$("#xmldata").text(data);        	 		         	 			      	 		
     				},
         	error:function(){       						// callback failed
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
				<form id="queryords">
					<div>merchant_code：<input Type="text" Name="merchant_code" id="merchant_code" value="1111110166"> * </div>
					<div>service_type：<input Type="text" Name="service_type" id="service_type" value="single_trade_query"> * </div>
					<div>interface_version：<input Type="text" Name="interface_version" id="interface_version" value="V3.0"/> * </div>					
					<div>sign_type：
						<select name="sign_type" id="sign_type">
							<option value="RSA-S">RSA-S</option>
							<option value="RSA">RSA</option>
						</select> *	</div>		
					<div>order_no：<input Type="text" Name="order_no" id="order_no" > * </div>
					<div>trade_no：<input Type="text" Name="trade_no" id="trade_no" > </div>																																		
				</form>
				<button id="submit">submit</button> 
			</td>
		</tr>
		<tr>
			<td>
				<div>Show Return Result：</div>
				<textarea rows="12" cols="90" id="xmldata"></textarea>
			</td>
		</tr>
	</table>
  </body>
</html>