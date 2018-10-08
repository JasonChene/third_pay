<?php
	$queryUrl=$_POST['queryUrl'];//提交网址
	$createTime=$_POST['createTime'];//充值时间
	$currentTime=$_POST['currentTime'];//充值时间
	$merchant=$_POST['merchant'];//商户号
	$orderNo=$_POST['orderNo'];//流水号
	$key=$_POST['key'];//md5密钥（KEY）

	$sign="createTime=".$createTime."&currentTime=".$currentTime."&merchant=".$merchant."&orderNo=".$orderNo."#".$key;
	$sign=MD5($sign);
	$post_data = array(
		"createTime"=>$createTime,
		"currentTime"=>$currentTime,
		"merchant"=>$merchant,
		"orderNo"=>$orderNo,
		"sign"=>$sign
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL,$queryUrl);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result=curl_exec($ch);
	header("Content-type: text/html; charset=utf-8");
	$abc = json_decode($result,true);
	//echo $result;
	if($abc["data"]["payFlag"]==1){
		$payFlag = "未支付";
	}else if($abc["data"]["payFlag"]==2){
		$payFlag = "已支付";
	}else{
		$payFlag = "已关闭";
	}
?>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no,minimal-ui">
	<meta name="format-detection" content="telephone=no" />
	<title>查询接口</title>
		
	<style type="text/css">
	body{padding:0;margin:0;color:#333;font-size:14px;font-family:微软雅黑;}
	ul,li{padding:0;margin:0;list-style:none;}
	.top{width:100%; height:40px;position: fixed; top:0;left:0;z-index:100;}
	.top-head{background-color:#18b4ed; text-align:center;color:#fff; height:45px; line-height:45px;}
	.top-nav{ height:45px; line-height:45px; background-color:#f0f0f0;}
	.top-nav li{width:50%; float:left;color:#777;border-bottom:2px solid #777; text-align:center;}
	.top-nav li.thisclass{border-bottom:2px solid #e70000;color: #e70000;background-color:#fff;}
	.content{width:98%;padding:1%;position:absolute; top:60px;left:0;z-index:99;}
	.ui-form-item{ height:45px; line-height:40px; width:100%;padding:5px 0;}
	.ui-form-item label{min-width:100px;width:32%; display:block; float:left; line-height:20px;}
	input,select{ height:40px;line-height:40px; float:left; padding:0 1%;color:#18b4ed;width:65%;font-size:13px;}
	.ui-btn-lg{ background-color:#f75549;color:#fff; height:45px; line-height:45px; text-align:center;border-radius:3px; margin-top:10px;cursor:pointer;}
	.top-nav li{cursor:pointer;}
	</style>
</head>
<body>
	<div style="width:100%; min-width:320px;">
		<div class="top">
			<div class="top-head">查询结果</div>
		</div>
		<div class="content">
			<div class="content-check">
					<div class="ui-form-item">
						<label>
							<div>商户号</div>
							<div style="font-size:12px;"> ( merchant )</div>
						</label>
						<input type="text" value="<?php echo $merchant; ?>"/>
					</div>
					<div class="ui-form-item">
						<label>
							<div>商户订单号</div>
							<div style="font-size:12px;"> ( orderNo )</div>
						</label>
						<input type="text" value="<?php echo $orderNo; ?>"/>
					</div>
					<div class="ui-form-item">
						<label>
							<div>系统订单号</div>
							<div style="font-size:12px;"> ( systemNo )</div>
						</label>
						<input type="text" value="<?php echo $abc["data"]["systemNo"]; ?>"/>
					</div>
					<div class="ui-form-item">
						<label>
							<div>创建时间</div>
							<div style="font-size:12px;"> ( createTime )</div>
						</label>
						<input type="text" value="<?php echo $createTime; ?>"/>
					</div>
					<div class="ui-form-item">
						<label>
							<div>充值金额</div>
							<div style="font-size:12px;"> ( amount )</div>
						</label>
						<input type="text" value="<?php echo $abc["data"]["amount"]; ?>"/>
					</div>
					<div class="ui-form-item">
						<label>
							<div>订单状态</div>
							<div style="font-size:12px;"> ( payFlag )</div>
						</label>
						<input type="text" value="<?php echo $payFlag ?>"/>
					</div>
			</div>
		</div>
	</div>
</body>
</html>
