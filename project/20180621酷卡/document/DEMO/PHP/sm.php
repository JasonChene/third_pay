<!DOCTYPE html>
<?php
date_default_timezone_set('Asia/Shanghai');
header("Content-type: text/html; charset=utf-8");
?>
<html>
<head>
    <title>扫码支付-酷卡支付</title>
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
	
	    <div class="new_demo" style="height:370px;">
      <div class="demo_thead" style="line-height:22px;background-color:#75b750;">
        <div class="demo_td_icon" style="margin-left:19px;">
          <h4>选择支付方式：</h4>
        </div>
		</br>
    <div id="choose_method">
					<div class="product-param left">
							<span class="alizi-radio-group">
							<span class="alizi-radio" onclick="payment('zfbsm')">
							<input id="zfbsm" name="bankType" type="radio" checked="" value="992"><label for="zfbsm"></label></span>
							&nbsp;<img src="bank/zfb.png" alt="citic" width="130" height="52" />
							</span>
							
							<span class="alizi-radio-group">
							<span class="alizi-radio" onclick="payment('wxsm')">
							<input id="wxsm" name="bankType" type="radio" value="1004"><label for="wxsm"></label></span>
							&nbsp;<img src="bank/wx.png" alt="citic" width="130" height="52" />
							</span>
							
							<span class="alizi-radio-group">
							<span class="alizi-radio" onclick="payment('qqsm')">
							<input id="qqsm" name="bankType" type="radio" value="993"><label for="qqsm"></label></span>
							&nbsp;<img src="bank/qq.png" alt="citic" width="130" height="52" />
							</span>
							
							<span class="alizi-radio-group">
							<span class="alizi-radio" onclick="payment('ylsm')">
							<input id="ylsm" name="bankType" type="radio" value="1006"><label for="ylsm"></label></span>
							&nbsp;<img src="bank/yl.png" alt="citic" width="130" height="52" />
							</span>
							
							<span class="alizi-radio-group">
							<span class="alizi-radio" onclick="payment('jdsm')">
							<input id="jdsm" name="bankType" type="radio" value="1007"><label for="jdsm"></label></span>
							&nbsp;<img src="bank/jd.png" alt="citic" width="130" height="52" />
							</span>
					</div>

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