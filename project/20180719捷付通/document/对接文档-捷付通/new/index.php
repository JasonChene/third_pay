<?php
  include('./config.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta content="email=no" name="format-detection">
	<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js"></script>
	<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap-theme.min.css">
    <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<title>测试页面</title>
</head>
<body>
<div class="panel panel-default">

    <div class="panel-heading">
        <h3 class="panel-title">
            演示
        </h3>
        <h5 class="panel-title" >
            <?php echo $fxid ?>
            <?php echo $fxgetway ?>
        </h5>
    </div>
    <div class="panel-body">
      <div class="form-group">
		<div class="input-group">
              <select class="fxmobile form-control" name="fxmobile">
                  <option value="0" >电脑网站</option>
                  <option value="1" >手机网站</option>
              </select>
          </div>
      </div>
	  <div class="form-group">
		<div class="input-group">
		  <input type="text" class="fxfee form-control" value="1.00" placeholder="请输入金额">
		  <div class="fxfee input-group-addon">元</div>
		</div>
	  </div>
      <div class="form-group">
        <img id="payimg"></img>
	  </div>
	  <button class="paybtn btn btn-primary">支付</button>
    </div>
</div>
<?php
function isMobile() { 
  // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
  if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
    return true;
  } 
  // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
  if (isset($_SERVER['HTTP_VIA'])) { 
    // 找不到为flase,否则为true
    return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
  } 
  // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
  if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger'); 
    // 从HTTP_USER_AGENT中查找手机浏览器的关键字
    if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
      return true;
    } 
  } 
  // 协议法，因为有可能不准确，放到最后判断
  if (isset ($_SERVER['HTTP_ACCEPT'])) { 
    // 如果只支持wml并且不支持html那一定是移动设备
    // 如果支持wml和html但是wml在html之前则是移动设备
    if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
      return true;
    } 
  } 
  return false;
}
  ?>
<iframe id="myFrameId" name="myFrameName" scrolling="no" frameborder="0" style="width: 200px; height : 100px;"></iframe>
        <script>
            $(document).ready(function () {
              	<?php if(isMobile()) {  ?>
					$(".fxmobile").val(1);
              	<?php }  ?>
              
                $('.paybtn').on('click', 
				function () {
					//
                    var fxfee = $('.fxfee').val();
                    var fxmobile = $('.fxmobile').val();
					//
                    if (parseFloat(fxfee) < 0.01) {
                        alert('请填写正确的支付金额。');
                        return;
                    }
					
					//
					$(".paybtn").attr({"disabled":"disabled"});
					
					//
                    $.post('./pay.php', {'fxfee': fxfee, 'fxmobile': fxmobile, 't': Math.random()}, 
					  function (data) {
						$("#respContent").val(data);
						var result = $.parseJSON(data);
						
						$(".paybtn").removeAttr("disabled");
                        if (result.status == '0') {
                            return;
                        }
						
                        if (result.status == '1') {
                            if(result.type == '0') {	//当面付
							  <?php if(isMobile()) {  ?>
                                $("#myFrameId").attr("src", "alipayqr://platformapi/startapp?saId=10000007&clientVersion=3.7.0.0718&qrcode=" + result.payurl);
                              <?php } else {  ?>
                                $("#payimg").attr("src", result.payimg);
                              <?php }  ?>
                            }
                          	if(result.type == '1' || result.type == '2') {	//移动端和电脑端支付
							  //window.location.href = result.payurl; 
							  windows.open(result.payurl);
                            }
							  
                            return;
                        }
                    });
                });
            });

        </script>
		<textarea id="respContent" > </textarea>
		<div style="display:block" ><iframe id="openFrame" name="openFrame" scrolling="no" frameborder="0" style="width: 100%; height : 200px;"  ></iframe></div>
</body>
</html>
