<?php
require_once 'inc.php';
//$userkey='cd54ba7719d318d5564c7ed7e19eaee65cb99c43';

$postdata=array();

$postdata['customerid']=$_POST['customerid'];
$postdata['data']=array();

$postdata['data'][]=BuildRow('');
$postdata['data'][]=BuildRow('1');

Post(json_encode($postdata));

function Post($data)
{
    //初始化
    $curl = curl_init();

//    $url='http://pay.wzasw.com/behalf';
    $url='http://www.8688pay.cn/behalf';
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


function BuildRow($index)
{
    $row=array();

    global $userkey;
    $customerid=$_POST['customerid'];

    $row['bankname']=$_POST['bankname'.$index];
    $row['province']=$_POST['province'.$index];
    $row['city']=$_POST['city'.$index];
    $row['branchname']=$_POST['branchname'.$index];
    $row['accountname']=$_POST['accountname'.$index];
    $row['cardno']=$_POST['cardno'.$index];
    $row['total_fee']=$_POST['total_fee'.$index];
    $row['notifyurl']=$_POST['notifyurl'.$index];
    $row['sdorderno']=$_POST['sdorderno'.$index];

    $signStr = 'customerid=' . $customerid . '&total_fee=' . $row['total_fee'] . '&sdorderno=' . $row['sdorderno'] . '&notifyurl=' .
        $row['notifyurl'] .  '&' . $userkey;
    $mysign =(md5($signStr));

    $row['sign']=$mysign;

    return $row;
}
?>

