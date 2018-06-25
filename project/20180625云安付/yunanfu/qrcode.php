<?php
if(isset($_GET['type']))
{
	if($_GET['type'] == "wx")
	{
		$title = "微信扫一扫付款";
		$openMobile = "打开手机微信";
		$payComment = "微信在线充值说明：";
	}
	else if($_GET['type'] == "zfb")
	{
		$title = "支付宝扫一扫付款";
		$openMobile = "打开手机支付宝";
		$payComment = "支付宝在线充值说明：";
	}
}
?>
<html>
	<head>
		<title>识别二维码付款</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/index.css" media="all">
		<script src="js/jquery.min.1.7.2.js" type="text/javascript" ></script>
		<script src='js/jquery.qrcode.js' type="text/javascript"></script>
		<script src='js/utf.js' type="text/javascript"></script>
	</head>
	<script>
		<?php 
			$code = '';
			if (isset($_GET['code'])){
				$code = $_GET['code'];
			}
			echo "qrcode_url = '$code'";
		?>
		
	</script>
	<body>
		<div class="weixinBox">

			<div class="qrcode">
				<img class="img" id="code" src="" />
				<div class="sub_title">
					<img class="wxsm" src="images/mm.png" />
					<p><?=$openMobile?><br/>用扫一扫付款</p>
				</div>
				<div class="extension">
					<div class="item">
						<div class="cont">
							<h4 class="title"><?=$payComment?></h4>
							<p class="desc">
							1.长按二维码图片，保存该二维码图片。<br/>
							2.<?=$openMobile?>，点击右上角的“+”选择“扫一扫” 点击右上角的相册，选择刚才保存的二维码，即可进行支付。<br/>
							3.支付成功后，会自动充值到会员账号。请删除二维码图片，防止下次充值失误。
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

<script type="text/javascript">
	if (qrcode_url == null){
				
	}else{
		$("#code").qrcode({ 
		width: 650,
		height: 650,
		text: qrcode_url
		});
		$(function(){
			var type = "png";
			var oCanvas = document.getElementById("myCanvas");
			var imgData = oCanvas.toDataURL(type);
			var qrcode = document.getElementById("code");
			qrcode.src = imgData;
		});
			
	}
			
</script>	
