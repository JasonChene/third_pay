<!DOCTYPE html>
<?php
date_default_timezone_set('Asia/Shanghai');
header("Content-type: text/html; charset=utf-8");
?>
<html>
<head>
    <title>网银支付-酷卡支付</title>
	<link type="text/css" rel="stylesheet" href="css/index.css"/>
	<link href="css/style.css?v=Alizi-V1.9.8.6" rel="stylesheet"/>
</head>
<body>
<script>
function amount(){
document.getElementById("order_amount2").value=document.getElementById("order_amount1").value
}
</script>
<!--主体-->
<div class="new_wrap">
  <div class="pay-demo">
  <form id="mainForm"  action="paySubmit.php" method="POST" target="_blank">
    <div class="new_demo">
	
      <div class="demo_thead">
        <div class="demo_td_icon">商品</div>
        <div class="demo_td_price">单价</div>
        <div class="demo_td_num">数量</div>
        <div class="demo_td_sum">买家</div>
      </div> 
	  
      <div class="demo_tr">
        <div class="demo_td_icon" style="width:420px; margin-left:20px;">
          <img src="images/shop_icon.png"> 
          <span>酷卡在线支付体验服务</span>
        </div>
        <div class="demo_td_price" id="demo_td_price">
        <input type="text" name="order_amount" class="input-text left" id="order_amount1" value="1.00" style="width:20%" onchange="amount()">
        </div>
        <div class="demo_td_num">
         <input type="button" class="input-text left" value="1" style="width:20%;background:white;" >
        </div>
        <div class="demo_td_sum" id="demo_td_sum">
        <input type="text" name="yonghuming" class="input-text left" value="admin" style="width:62%;background:white;" >
        </div>
      </div>
	  
      <div id="demo_money">
        <span class="demo_money_name">订单金额：</span>
        <span class="demo_money_value" id="demo_money_value">
		¥ <input type="text" name="order_amount" class="input-text left" id="order_amount2" value="1.00"  style="width:25%;border:none;font-size:32px;color:#f65405;" onchange="amount()"> 元
		</span>
      </div>
	  
    </div>
	
	    <div class="new_demo" style="height:540px;">
      <div class="demo_thead" style="line-height:22px;background-color:#75b750;">
        <div class="demo_td_icon" style="margin-left:19px;">
          <h4>选择支付方式：</h4>
        </div>
		</br>
    <div id="choose_method">
		<table>
          <tr>
			<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank1')">
			<input id="bank1" name="bankType" type="radio" value="967" checked=""><label for="bank1"></label></span>
			&nbsp;<img src="bank/zggsyh.gif" alt="icbc" width="130" height="52" />
			</span>
			
			<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank2')">
			<input id="bank2" name="bankType" type="radio" value="964"><label for="bank2"></label></span>
			&nbsp;<img src="bank/zgnyyh.gif" alt="abc" width="130" height="52" />
			</span>
			
			<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank3')">
			<input id="bank3" name="bankType" type="radio" value="963"><label for="bank3"></label></span>
			&nbsp;<img src="bank/zgyh.gif" alt="boc" width="130" height="52" /> 
			</span>
			
			<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank4')">
			<input id="bank4" name="bankType" type="radio" value="981"><label for="bank4"></label></span>
			&nbsp;<img src="bank/jtyh.gif" alt="comm" width="130" height="52" />
			</span>
			
			<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank5')">
			<input id="bank5" name="bankType" type="radio" value="965"><label for="bank5"></label></span>
			&nbsp;<img src="bank/zgjsyh.gif" alt="ccb" width="130" height="52" />
			</span>

          </tr>
		  </br></br>
          <tr >
		  	<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank6')">
			<input id="bank6" name="bankType" type="radio" value="970"><label for="bank6"></label></span>
			&nbsp;<img src="bank/zsyh.gif" alt="cmb" width="130" height="52" />
			</span>
			
			<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank7')">
			<input id="bank7" name="bankType" type="radio" value="977"><label for="bank7"></label></span>
			&nbsp;<img src="bank/pdfzyh.gif" alt="spdb" width="130" height="52" />
			</span>
			
			<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank8')">
			<input id="bank8" name="bankType" type="radio" value="980"><label for="bank8"></label></span>
			&nbsp;<img src="bank/zgmsyh.gif" alt="cmbc" width="130" height="52" />
			</span>
			
			<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank9')">
			<input id="bank9" name="bankType" type="radio" value="972"><label for="bank9"></label></span>
			&nbsp;<img src="bank/xyyh.gif" alt="cib" width="130" height="52" />
			</span>
			
			<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank10')">
			<input id="bank10" name="bankType" type="radio" value="986"><label for="bank10"></label></span>
			&nbsp;<img src="bank/zggdyh.gif" alt="ceb" width="130" height="52" />
			</span>

			
          </tr>
		  </br></br>
          <tr>
		  	<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank11')">
			<input id="bank11" name="bankType" type="radio" value="985"><label for="bank11"></label></span>
			&nbsp;<img src="bank/gdfzyh.gif" alt="cgb" width="130" height="52" />
			</span>
			
			<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank12')">
			<input id="bank12" name="bankType" type="radio" value="971"><label for="bank12"></label></span>
			&nbsp;<img src="bank/yzcxyh.gif" alt="psbc" width="130" height="52" />
			</span>
			
			<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank13')">
			<input id="bank13" name="bankType" type="radio" value="962"><label for="bank13"></label></span>
			&nbsp;<img src="bank/zxyh.gif" alt="citic" width="130" height="52" />
			</span>
			
			<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank14')">
			<input id="bank14" name="bankType" type="radio" value="978"><label for="bank14"></label></span>
			&nbsp;<img src="bank/payh.gif" alt="psbc" width="130" height="52" />
			</span>
			
			<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank15')">
			<input id="bank15" name="bankType" type="radio" value="975"><label for="bank15"></label></span>
			&nbsp;<img src="bank/shyh.gif" alt="psbc" width="130" height="52" />
			</span>

          </tr>
		  </br></br>
          <tr> 
		  	<span class="alizi-radio-group">
			<span class="alizi-radio" onclick="payment('bank16')">
			<input id="bank16" name="bankType" type="radio" value="982"><label for="bank16"></label></span>
			&nbsp;<img src="bank/hxyh.gif" alt="citic" width="130" height="52" />
			</span>
          </tr>
        </table> 

      <p style="text-align:center;">
        <input type="submit" value="提 交" class="submit_btn" />
      </p>
      <div class="methods_shuoming">
        <span>
          *
        </span>本次体验为真实环境，体验金额不予退还，敬请谅解</div>
    </div> 
      </div> 
    </div>
  </form>
</div>
</div>


</form>
</body>
</html> 