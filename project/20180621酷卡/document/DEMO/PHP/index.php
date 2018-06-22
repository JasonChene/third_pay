<!DOCTYPE html>
<?php
date_default_timezone_set('Asia/Shanghai');
header("Content-type: text/html; charset=utf-8");
?>
<head>
	<title>酷卡产品体验中心</title>
	<link href="css/main.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<body>

<!--content-->
<div class="w945 ofh">
	<ul class="w965 productlist ofh">
		
		<li>
			<div class="toll">
				<div class="toll_img">
					<div class="producticon pcQuickPay">
						<img src="images/pcQuickPay.png" class="mt40" />
					</div>
					<div class="productname">PC快捷支付</div>
				</div>
				<div class="toll_info">
					<div class="producticon graybg">
						<div class="mt15">
							<span>1、操作简单便捷</span> <span>2、用户覆盖面广泛</span> <span>3、支付网关轻松接入</span>
						</div>
					</div>
					<div class="productname greentitle"><a class="productname greentitle" target="_blank" href="pc.php">点我体验</a></div>
				</div>
			</div>
		</li>

		<li>
			<div class="toll">
				<div class="toll_img">
					<div class="producticon aggregationZs">
						
						<img src="images/jhzf_zs.png" class="mt40" />
					</div>
					<div class="productname">扫码支付</div>
				</div>
				<div class="toll_info">
					<div class="producticon graybg">
						<div class="mt15">
							<span>1、只需一个二维码</span> <span>2、接入主流移动支付</span> <span>3、满足更多使用场景支付</span>
						</div>
					</div>
					<div class="productname greentitle"><a class="productname greentitle" target="_blank" href="sm.php">点我体验</a></div>
				</div>
			</div>
		</li>	
		
		<li>
			<div class="toll">
				<div class="toll_img">
					<div class="producticon quickPayWap">
						<img src="images/wapQuickPay.png" class="mt40" />
					</div>
					<div class="productname">H5手机支付</div>
				</div>
				<div class="toll_info">
					<div class="producticon graybg">
						<img src="images/quickPayWap.png" width="130" class="vpic" />
					</div>
					<div class="productname greentitle">扫我体验</div>
				</div>
			</div>
		</li>

	</ul>
</div>

<script>
	$(document).ready(function() {
		$('.toll').mouseover(function() {
			$(this).stop().animate({
				"right" : "-300px"
			}, 100);
		});
		$('.toll').mouseout(function() {
			$(this).stop().animate({
				"right" : "0"
			}, 100);
		});
	});
</script>

