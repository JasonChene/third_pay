<?php
header("Content-type: text/html; charset=utf-8");
function curl_post_https($url,$data){ // 模拟提交数据函数
    $headers=array('Content-Type: application/json');
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        echo 'Errno'.curl_error($curl);//捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据，json格式
}
function curl_get_https($url){
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
    $tmpInfo = curl_exec($curl);     //返回api的json对象
    //关闭URL请求
    curl_close($curl);
    return $tmpInfo;    //返回json对象
}


function get_sign($str){
    return strtoupper(md5($str));
}

/*{"version":"1.0","merId":"10001","notify":"","orderId":"T1491445348661","redirectUr l":"","remark":"hello","sign":"503374D0333610080A03F602C5A3994E","totalMoney
":"1","tradeType":"alipay ","describe":"测试商品"，" fromtype ":" wap"}

{value}要替换成接收到的值，{key}要替换成平台分配的接入密钥，可在商户后台获取. merId={value}&orderId={value}&totalMoney={value}&tradeType={value}&
{key}使用 md5 签名上面拼接的字符串即可生成 32 位密文再转换成大写
*
*/
function addorder(){
    $url="http://www.1906yogv.com:9091/business/order/prepareOrder";
    $data['version']='1.0';
    $data['merId']='10003';
    $data['notify']=''; //传入数据通知地址
    $data['orderId']='T1491445348661'; //生成订单号必须唯一
    $data['redirectUrl']=''; //支付跳转地址
    $data['remark']='hello';
    $data['totalMoney']='100';
    $data['tradeType']='wecaht';
    $data['describe']='产品信息';
    $data['fromtype']='wap';
    $str='merId='.$data['merId'].'&orderId='.$data['orderId'].'&totalMoney='.$data['totalMoney'].'&tradeType='.$data['tradeType'].'&ac8d6f81091e49cfb4769de549dbb770';
    //$str='merId=".$data['merId']."&orderId="$data['orderId']"&totalMoney=".$data['totalMoney']."&tradeType=alipay&ac8d6f81091e49cfb4769de549dbb770";
    $data['sign']=get_sign($str);
    $data=json_encode($data);
    $rs =curl_post_https($url, $data);
    $res= json_decode($rs,true);
    return $res['object']['wxPayWay'];   
}

function query(){
    $url="http://www.1906yogv.com:9091/business/order/query";
    $data['merId']='10001';
    $data['orderId']='1509612336238';
    $str='merId='.$data['merId'].'&orderId='.$data['orderId'].'&efddc032eccbd28709d934adf351ca67';
    $data['sign']=get_sign($str);
    
    $data=json_encode($data);
    $rs =curl_post_https($url, $data);
    $res= json_decode($rs,true);
    return $res;
}



 





//一、下单接口
//$rs=addorder();
//var_dump($rs);
//二、订单查询接口
$rs=query();
var_dump($rs);












