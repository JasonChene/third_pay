<?php
//此文件为支付配置文件
@header("Content-type: text/html;charset=utf-8");
//商户ID
$config['partner']      = "";

//商户key
$config['key']          = "";

//同步返回地址
$config['return_url']   = "http://".$_SERVER['HTTP_HOST'].web_path()."/return.php";

//异步返回地址
$config['notify_url']   = "http://".$_SERVER['HTTP_HOST'].web_path()."/notify.php";

//网站编码
$config['charset']      = 'utf-8';

//支付请求地址,默认不能修改
$config['pay_url']      = "http://pay.8808068.com/pay";


//数组转URL地址
function arr_url($arr) {
	$arg  = "";
	foreach($arr as $key=>$val){
		$arg.=$key."=".$val."&";
	}
	$arg = substr($arg,0,count($arg)-2);
	return $arg;
}
//生成签名
function md5_sign($arr,$skey) {
	$arr_filter = array();
	foreach($arr as $key=>$val){
		if($key == "sign" || $val == "") continue;
		$arr_filter[$key] = $arr[$key];
	}
	//对数组排序
	ksort($arr_filter);
	reset($arr_filter);
	//转URL
	$arg = arr_url($arr_filter);
	//MD5加密
	$sign =  strtoupper(md5($arg.$skey));
	return $sign;
}
//获取网站运行目录
function web_path(){
	$uri = 'http://cscms'.$_SERVER['REQUEST_URI'];
	$arr = parse_url($uri);
	$arr2 = explode('/', $arr['path']);
	$path = array();
	foreach ($arr2 as $key => $value) {
		if(substr($value,-4)!='.php'){
			$path[]=$value;
		}
	}
	return implode('/',$path);
}
//获取支付二维码
function get_ma($payurl,$dingdan='',$title='微信'){

	//远程获取二维码
	$json = file_get_contents($payurl);
	$json = iconv("UTF-8", "GB2312//IGNORE", $json);
	$arr = json_decode($json,1);
	//返回数据
	if($arr['code']==0){
		$code_url = $arr['code_img_url'];
	}else{
		exit($arr['msg']);
	}
	if(empty($code_url)) exit('提交订单失败，请稍后再试~!');

	echo '<html><head><meta http-equiv="content-type" content="text/html;charset=utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1" /> <title>'.$title.'支付 - 香蕉支付</title><script type="text/javascript" src="jquery.min.js"></script></head><body style="width:100%;margin:0 auto;margin-top:10px;text-align:center;"><p style="color:#556B2F;">请用手机打开'.$title.'，扫一扫付款</p><img  src="'.$code_url.'" style="width:150px;height:150px;"/><script type="text/javascript">var payts = setInterval("wxpay();",2000);function wxpay(){$.get("'.web_path().'/init.php?dingdan='.$dingdan.'",function(data) {if(data!="no"){ $("body").html(data);clearInterval(payts); }});}</script></body></html>';
	exit;
}
//读取写入订单，测试用，正式使用不能这样操作
function get_dingdan($dingdan,$n=0){
	$str = file_get_contents('./dingdan.txt');
	if($n==0){
		$str = $dingdan."|0###".$str;
		file_put_contents('./dingdan.txt',$str);
	}else{
		$narr = array();
		$arr = explode("###", $str);
		foreach($arr as $k=>$v){
			if(!empty($v)){
				$str1 = explode("|", $v);
				if($str1[0] == $dingdan){
					if($n==2){
						return $str1[1];
					}else{
						$narr[] = $str1[0]."|1";
					}
				}else{
					if($n==1) $narr[] = $v;
				}
			}
		}
		if($n==2){
			return 0;
		}else{
			$str = !empty($narr) ? implode("###",$narr) : '';
			file_put_contents('./dingdan.txt',$str);
		}
	}
	return true;
}