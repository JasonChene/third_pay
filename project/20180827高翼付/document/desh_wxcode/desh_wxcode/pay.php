<?php
require_once 'inc.php';
$arr['version']='1.0';
$arr['customerid']=$userid;
$arr['total_fee']=number_format($_POST['total_fee'],2,'.','');
$arr['sdorderno']=time()+mt_rand(1000,9999);
$arr['notifyurl']=$_POST['notifyurl'];
$arr['returnurl']=$_POST['returnurl'];
//遍历数组进行字符串的拼接
$temp = "";
foreach ($arr as $x=>$x_value){
	if ($x_value != null){
		$temp = $temp.$x."=".$x_value."&";
	}
}
/* echo $temp.$userkey; 
echo "<br>";
echo 'version='.$arr['version'].'&customerid='.$arr['customerid'].'&total_fee='.$arr['total_fee'].'&sdorderno='.$arr['sdorderno'].'&notifyurl='.$arr['notifyurl'].'&returnurl='.$arr['returnurl'].'&'.$userkey;
die(); */
$arr['sign']=md5($temp.$userkey);
$arr['paytype']=$_POST['paytype'];
$arr['bankcode']=$_POST['bankcode'];
$arr['remark']='';
$arr['get_code']=$_POST['get_code'];


//$arr['sign']=md5('version='.$arr['version'].'&customerid='.$arr['customerid'].'&total_fee='.$arr['total_fee'].'&sdorderno='.$arr['sdorderno'].'&notifyurl='.$arr['notifyurl'].'&returnurl='.$arr['returnurl'].'&'.$userkey);

$url = "https://www.goiflink.com/apisubmit";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL,$url);//设置抓取的url
curl_setopt($curl, CURLOPT_HEADER, false);//设置头文件的信息作为数据流输出
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//设置获取的信息以文件流的形式返回，而不是直接输出。
curl_setopt($curl, CURLOPT_POST, 1);//设置post方式提交
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查  
curl_setopt($curl, CURLOPT_POSTFIELDS, $arr);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
$data = curl_exec($curl);//执行命令
curl_close($curl);//关闭URL请求
//echo ' 返回-----' .$data;//data为二维码地址
$data = json_decode($data);
$code = $data->code_info;
include 'phpqrcode/phpqrcode.php';  //引入phpqrcode类文件
QRcode::png($code, 'qrcode5.png', 'L', 6, 2);  //生成二维码
echo "<img src='qrcode5.png'>";

?>
