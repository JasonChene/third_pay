<?php
ini_set('date.timezone','Asia/Shanghai');
$record = date("YmdHis",time());

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="layui/css/layui.css"  media="all">
</head>
<body>    
<form class="layui-form" style="margin-top: 20px;" method="post" action="recharge.php" target="_blank">
  <div class="layui-form-item">
    <label class="layui-form-label">订单号</label>
    <div class="layui-input-block">
    <input name="record" value="<?php echo $record?>">
    </div>
  </div>
 

  <div class="layui-form-item">
    <label class="layui-form-label">支付方式</label>
    <div class="layui-input-block">
      <input type="radio" name="sdk" value="5732b4bf72c22ffada3aa2f4a1" title="支付宝" checked>
	  <div class="layui-unselect layui-form-radio layui-form-radioed"><div>支付宝</div></div>
      <input type="radio" name="sdk" value="5db5f77c259010278a75cff6a1" title="微信">
	  <div class="layui-unselect layui-form-radio"><div>微信</div></div>
	     
    </div>
  </div>
  
  <div class="layui-form-item">
    <label class="layui-form-label">金额</label>
    <div class="layui-input-block">
      <input type="text" name="money" value="0.01" class="layui-input" style="width: 98%;">
          <input type="hidden" name="refer" value="http://www.1qcz.com/demo/hrefback.php">
      <div class="layui-form-mid" style="color:red;">注意：请一定按照二维码的金额转账，否则无法到账</div>
    </div>
  </div>


  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" type="submit">确认充值</button>
    </div>
  </div>
</form>
 
<script src="<?php echo _pub;?>layui/layui.js" charset="utf-8"></script>
<script src="<?php echo _theme;?>js/jquery.min.js" charset="utf-8"></script>
<script>
layui.use(['form', 'layedit'], function(){
  var form = layui.form
  ,layer = layui.layer
  ,layedit = layui.layedit;
//添加
});

</script>
</body>
</html>