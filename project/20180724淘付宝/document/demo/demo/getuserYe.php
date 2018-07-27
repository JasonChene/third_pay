<?php
$userkey='3008dbf5aefa06b5af3899329a55b91a586b2f64';

$postdata=array();

$postdata['customerid']=$_POST['customerid'];
$signStr = 'customerid=' . $_POST['customerid']  . $userkey;
$mysign =(md5($signStr));
$postdata['sign']=$mysign;

Post($postdata);

function Post($data)
{
    //初始化
    $curl = curl_init();

//    $url='http://pay.wzasw.com/behalf';
    $url='http://pay.wzasw.com/userYe';
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    //显示获得的数据
    die($data);
}

?>

