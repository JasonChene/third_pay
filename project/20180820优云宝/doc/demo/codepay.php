<?php
error_reporting(0);//PHP报错不显示
header("content-Type: text/html; charset=Utf-8"); 
//导入配置文件 一般配置这个文件即可 如果你是高手任你发挥
require_once("config.php"); 

//将数据列入数组
$yundata = array(
   "appid"  => $congig['appid'],
   "data"   => $_REQUEST['data'],//网站订单号/或者账号
   "money"  => number_format($_REQUEST['money'],2,".",""),//注意金额一定要格式化否则token会出现错误
   "type"   => $_REQUEST['type'],
   "uip"    => $congig['uip'],
);


//token签名规则 注意顺序不能乱
//金额格式 例如正确格式(10.00  100.01  0.01)  金额必须格式化否则token签名会失败
//错误的格式 (10  20  500  2)
$token = array(
  "appid"  =>  $congig['appid'],//APPID号码
  "data"   =>  $yundata["data"],//数据单号
  "money"  =>  $yundata["money"],//金额
  "type"   =>  $yundata["type"],//类别
  "uip"    =>  $congig['uip'],//客户IP
  "appkey" =>  $congig['appkey']//appkey密匙
);

/*
token签名MD5加密 
将字符串进行MD5加密 
md5(appid=88888888&data=222222&money=100.00&type=1&uip=127.0.0.1&appkey=xxxxxxx)
签名一律小写 例如 ：528a657d628395de403d4d152d658073
*/
$token = md5(urlparams($token));
//整合数据拼接token一起发送
$postdata = urlparams($yundata).'&token='.$token;
//发送数据到网关
$fdata = curl_post_https($congig['server'],$postdata);

//将返回的json代码做进一步处理
$sdata = json_decode($fdata, true);//将json代码转换为数组

//返回的json参数
/*
{"state":"1","qrcode":"二维码","youorder":"token","data":"data","money":"10.00","times":"1531384783","orderstatus":"0","text":"10089"}
state = 1 为成功获取二维码数据  0表示异常 请看错误代码 
*/
$state = $sdata["state"];//状态 1 ok   0有错误

if(!$state){
	exit('异常'.$sdata["text"]);
}

$qrcode = $sdata["qrcode"];//二维码

$times =  $sdata["times"] - time(); //有效时间减去当前时间 保留一分钟减去60秒

$moneys = $sdata["money"];//实际付款金额

$orderstatus =$sdata["orderstatus"];//付款状态 1ok  0等待付款

$data =$sdata["data"];//传递的订单号

$order =$sdata["order"];//云端分配的唯一订单号 通过这个订单号查询状态

//
if($yundata["type"]==1){
	$logo = 'template/Image/zfb.png';
	$title = '支付宝';	
	$text =  '支付宝扫一扫付款（手机上可以直接启动APP，或者截图相册识别）';
	//如果你只使用支付宝 固定金额 可以做成自动启动支付宝APP  具体查阅开发文档或询问技术
}elseif($yundata["type"]==2){
	$logo = 'template/Image/qq.png';
	$title = 'QQ钱包';
	$text = 'QQ钱包扫一扫付款（QQ中可长按识别，或者截图相册识别）';
}elseif($yundata["type"]==3){
	$logo = 'template/Image/wx.png';
	$title = '微信支付';
	$text = '微信扫一扫付款（微信中可长按识别，或者截图相册识别）';
}elseif($yundata["type"]==4){
	$logo = 'template/Image/ysf.png';
	$title = '云闪付';
	$text = '银联云闪付扫一扫付款';
}
?>

<html class="no-js css-menubar" lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?=$title?></title>
	<link rel="stylesheet" href="template/css/bootstrap.css">
    <link rel="stylesheet" href="template/css/bootstrap-extend.css">
    <link rel="stylesheet" href="template/css/site.css">
	<script type="text/javascript" src="https://apps.bdimg.com/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="template/js/qrcode.js"></script>
</head>
  <body class="page-maintenance layout-full">
    <div class="page animsition text-center" style="-webkit-animation: 800ms; opacity: 1;">
      <div class="page-content vertical-align-middle">
          <!-- yunmapay -->
          <div id="pjax" class="container">
            <div class="row paypage-logo">
              <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-xs-12 paypage-logorow">
                <img src="<?=$logo?>" alt="<?=$title?>" width="94"></div>
            </div>
            <div class="row paypage-info">
              <div class="col-lg-6 col-lg-offset-2 col-md-7 col-md-offset-1 col-xs-10 col-xs-offset-0">
                <p class="paypage-desc">订单还有<strong id="minute_show"><s></s>00分</strong>
    <strong id="second_show"><s></s>00秒</strong>过期</p>
              </div>
              <div class="col-lg-2 col-md-3 col-xs-2 clearfix">
                <p class="paypage-price">
                  <span class="paypage-price-number"><?=$moneys?></span>元</p>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-xs-12 paypage-qrrow">
                <p id="paypage-tip"><font color="#000000" size="2">此二维码支付1次有效</font></p>
				<div align="center"><a id="qrcode"></a></div>
                <p id="paypage-order" class=""><span style="color:red;" ><?=$text?></span><br/><span style="color:#2f2f2f;font-size:15px;" >订单号:<?=$data?></span>
</p>
<?php
 //如果选择支付宝启动 提示用户直接点击启动APP完成付款 
 if($yundata["type"]==1){	
?>
<p class="animation-slide-bottom"><a class="btn btn-danger" onclick="isMobileapp()">启动支付宝手机APP付款</a></p>
<?php }?>
 <p class="animation-slide-bottom">
			 			 <a class="btn btn-danger" href="#">付款成功会自动跳转</a>	
						</p>							
				  </div>
		</div>
	  </div>
    </div>  
	
<footer class="site-footer">
<div class="site-footer-legal"></div>
<div class="site-footer-right">
<?=$title?></div>
</footer>
</div>
  </body></html>



<script type="text/javascript">
var intDiff = parseInt(<?=$times?>);//倒计时总秒数量
function timer(intDiff){
    window.setInterval(function(){
    var day=0,
        hour=0,
        minute=0,
        second=0;//时间默认值       
    if(intDiff > 0){
        day = Math.floor(intDiff / (60 * 60 * 24));
        hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
        minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
        second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
    }
	if (minute == 00 && second == 00){
		document.getElementById('qrcode').innerHTML='<br/><br/><br/><h4>订单超时</h5><br/><br/>';
		alert('订单超时');
		javascript :history.back(-1);
	}
    if (minute <= 9) minute = '0' + minute;
    if (second <= 9) second = '0' + second;
    $('#day_show').html(day+"天");
    $('#hour_show').html('<s id="h"></s>'+hour+'时');
    $('#minute_show').html('<s></s>'+minute+'分');
    $('#second_show').html('<s></s>'+second+'秒');
    intDiff--;
	
    }, 1000);
} 
$(function(){
    timer(intDiff);
});

// 设置参数方式
var qrcode = new QRCode('qrcode', {
  text: '<?=$qrcode?>',
  width: 168,
  height: 168,
  colorDark : '#000000',
  colorLight : '#ffffff',
  correctLevel : QRCode.CorrectLevel.M
});

 // 检查是否支付完成
    function loadmsg() {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "orderajax.php",
            timeout: 10000, //ajax请求超时时间10s
            data: {type: "<?=$yundata["type"]?>", order: "<?=$order?>", data: "<?=$data?>"}, //post数据
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                if (data.code == 1) {
                      document.getElementById('qrcode').innerHTML='<br/><br/><br/><h4>支付成功！</h4><br/><br/>';
					  //跳转地址
					 alert('支付完成！');
					 window.location.href='<?=$congig["reurl"]?>';
                }
				if (data.code == 2) {
					document.getElementById('qrcode').innerHTML='<br/><br/><br/><h4>订单超时</h4><br/><br/>';
					alert('订单超时');
					javascript :history.back(-1);
                }
				if (data.code == 3) {
					document.getElementById('qrcode').innerHTML='<br/><br/><br/><h4>订单丢失</h4><br/><br/>';
					alert('订单丢失');
					javascript :history.back(-1);
                }
				if (data.code == 4) {
					document.getElementById('qrcode').innerHTML='<br/><br/><br/><h4>配置有误token</h4><br/><br/>';
					alert('配置有误token');
					javascript :history.back(-1);
                }
				if (data.code == 0) {
                    setTimeout("loadmsg()", 5000);
                }
            },
            //Ajax请求超时，继续查询
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == "timeout") {
                    setTimeout("loadmsg()", 5000);
                } else { //异常
				 setTimeout("loadmsg()", 5000);
				 document.getElementById('qrcode').innerHTML='<br/><br/><br/><h4>链接失败</h4><br/><br/>';
				
                }
            }
        });
    }
	function isMobileapp() {
        window.location.href = "alipays://platformapi/startapp?saId=10000007&clientVersion=3.7.0.0718&qrcode=<?=$qrcode?>"
    }
	
    window.onload = loadmsg();
</script>
