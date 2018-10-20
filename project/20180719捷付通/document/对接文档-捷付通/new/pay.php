<?php
/**
 * 客户端请求本接口 实现支付
 * author: fengxing
 * Date: 2017/10/7
 */
include('./config.php');
$ddh = time() . mt_rand(100, 999); //商户订单号

//记录订单号及订单状态
file_put_contents('./ddh.txt',$ddh.'|0');

$data = array(
    "fxid" => $fxid, //商户号
    "fxddh" => $ddh, //商户订单号
    "fxdesc" => "test", //商品名
    "fxfee" => $_POST['fxfee'], //支付金额 单位元
    "fxattch" => 'mytest', //附加信息
    "fxnotifyurl" => $notifyUrl, //异步回调 , 支付结果以异步为准
    "fxbackurl" => $backUrl, //同步回调 不作为最终支付结果为准，请以异步回调为准
    "fxpay" => "alipay",
    "fxmobile" => $_POST['fxmobile'],
    "fxip" => getClientIP(0, true), //支付端ip地址
    'fxbankcode'=>'',
    'fxfs'=>'',
);

/*
{
"fxid": "2018128",
"fxddh": "no0d5426bff6e1ef69",
"fxdesc": "pay",
"fxfee": "1.00",
"fxnotifyurl": "http://dsf.dsvip88.com/callBack/no0d5426bff6e1ef69/JieFuTong",
"fxbackurl": "http://350gtv.com",
"fxpay": "alipay",
"fxsign": "1bc84b420aa00d2dd080055c682a8dac",
"fxip": "172.30.5.38"
}

  201810883708281747561300267
  
  http://pay.ls12.lxwaf.com/PayBack/JieFuTongNotify?fxid=2018108&fxddh=83708281747561300267&fxdesc=2018108&fxorder=&fxfee=100.26&fxattch=83708281747561300267&fxtime=0&fxstatus=1&fxsign=062e9aa594ea4cf28b50ae2477df367a	
$data["fxid"]="2018128";
$data["fxddh"]="no0d5426bff6e1ef69";
$data["fxdesc"]="pay";
$data["fxfee"]="1.00";
$data["fxnotifyurl"]="http://dsf.dsvip88.com/callBack/no0d5426bff6e1ef69/JieFuTong";
$data["fxbackurl"]="http://350gtv.com";
$data["fxpay"]="alipay";
$data["fxsign"]="1bc84b420aa00d2dd080055c682a8dac";
1bc84b420aa00d2dd080055c682a8dac
1bc84b420aa00d2dd080055c682a8dac
$data["fxip"]="172.30.5.38";

echo "<br>fxsign==".$data["fxsign"];

*/


$data["fxsign"] = md5($data["fxid"] . $data["fxddh"] . $data["fxfee"] . $data["fxnotifyurl"] . $fxkey); //加密


$r = getHttpContent($fxgetway, "POST", $data);
$backr = $r;
$r = json_decode($r, true); //json转数组

if(empty($r)) exit(print_r($backr)); //如果转换错误，原样输出返回

//验证返回信息

if ($r["status"] == 1) {
	echo json_encode($r);
    exit();
} else {
    //echo $r['error'].print_r($backr); //输出详细信息
    echo $r['error']; //输出错误信息
    exit();
}

?>