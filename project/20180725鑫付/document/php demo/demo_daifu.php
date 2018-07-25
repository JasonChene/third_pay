<?php  
  


function request_post($url = "", $param = "")
{
    if (empty($url) || empty($param)) {
        return false;
    }
    $postUrl = $url;
    $curlPost = $param;
    $ch = curl_init(); // 初始化curl
    curl_setopt($ch, CURLOPT_URL, $postUrl); // 抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0); // 设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1); // post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch); // 运行curl
    curl_close($ch);
    

    return $data;
}

//array转字符串
function array2string($array)
{
    $string = [];

    if ($array && is_array($array)) {

        foreach ($array as $key => $value) {
            $string[] = $key . "=" . $value;
        }
    }
    return implode("&", $string);
}
$key="";

$data["merchant_id"]="";  //商户id
$data["amount"]="";      //提现金额
$data["account_no"]="";  //银行账号
$data["account_name"]="";  //开户人名
$data["bank_name"]="中国工商银行府前路支行";
$data["bank_code"]="";    //联行号
$data["bank_general_name"]="中国工商银行";
$data["notify_url"]="";    //回调地址
$data["down_no"]=time().rand(100,999);   //单号

ksort($data);   //排序
$data_to_string=array2string($data);
$data["sign"]=strtoupper(MD5($data_to_string."&key=".$key));

$data=array2string($data);

$res=request_post("http://域名:8081/payout/order",$data);
//下面输出
$res=json_decode($res,true);
