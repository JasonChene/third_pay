<?php
error_reporting(0);//PHP报错不显示
header("content-Type: text/html; charset=Utf-8"); 
//导入配置文件 一般配置这个文件即可 如果你是高手任你发挥
require_once("config.php"); 

//这是一个异步获取订单状态的一个API

//建议高手吧订单状态做在本地数据库进行查询 这样会减少延迟，查询速度会更快，数据更准确。

//将主要数据列入数组
$yundata = array(
   "appid"  => $congig['appid'],//获取appid
   "order"  => $_REQUEST['order'],//这个是云端返回的一个唯一单号
   "type"   => $_REQUEST['type'],//获取分类
   "uip"    => $congig['uip']//获取用户IP地址
);

//订单查询签名格式
// md5(appid=666666&type=1&uip=127.0.0.1&appkey=123456789);
//以上是token签名规则
$token = array(
  "appid"  =>  $congig['appid'],//APPID号码
  "type"   =>  $yundata["type"],//类别
  "uip"    =>  $congig['uip'],//客户IP
  "appkey" =>  $congig['appkey']//appkey密匙
);

 //加密token 32位  小写
$token = md5(urlparams($token));
//exit($token);
//重组条件
$postdata = urlparams($yundata).'&token='.$token;

//订单查询网关地址后面加 order
$fdata = curl_post_https($congig['server'].'order',$postdata);

//$sdata = json_decode($fdata, true);//将json代码转换为数组
/*
json 返回的几个状态
完整输出JSON例子  {"code":"0","msg":"\u5f85\u4ed8\u6b3e"}
code = 0  等待付款
code = 1  付款完成
code = 2  订单超时
code = 3  订单不存在或者超时被删除
code = 4  token错误
*/
echo $fdata;
?>
