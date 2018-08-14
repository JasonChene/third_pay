<?php session_start(); ?>
<? header("content-Type: text/html; charset=UTF-8");?>
<?php
//error_reporting(1);
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");
//$top_uid = $_REQUEST['top_uid'];
if(function_exists("date_default_timezone_set"))
{
	date_default_timezone_set("Asia/Shanghai");
}
//获取订单资料
$params = array(':m_id'=>$_REQUEST['m_id'],':m_order'=>$_REQUEST['m_order']);
$sql = "select m_id,m_order,totalvaildbet,pay_card,pay_num,pay_address,pay_name from k_money where m_id=:m_id and m_order=:m_order and type='2' and df_disanfang is null";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$m_id = $row['m_id'];
$m_order = $row['m_order'];
$totalvaildbet = $row['totalvaildbet'];
$pay_card = $row['pay_card'];
$pay_num = $row['pay_num'];
$pay_address = $row['pay_address'];
$pay_name = $row['pay_name'];
//获取第三方的资料
$params = array(':pay_name'=>'泽圣');
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_name=:pay_name and is_df='1'";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$pay_type = $row['pay_type'];
if($pay_mid == "" || $pay_mkey == ""|| $pay_name == "")
{
    $retMsg = array('msg'=>'非法提交参数');
    echo json_encode($retMsg);
    exit;
}
    $merchantCode = $pay_mid;
	$md5Key       = $pay_mkey;
	$intoCardName = $pay_name;
	$intoCardNo   = $pay_num;
	$bankCode     = ""; // 必须空
	$bankName     = ""; // 必须空
	$intoCardType = "2"; // 1-对公 2-对私
	$remark       = "测试出款";
	$type         = "04"; // 03-非实时付款到银行卡;04-实时付款到银行卡
	$nonceStr     = "asdqawe";//随机生成，
	$outOrderId   = $m_order;//商户订单号
	$totalAmount  = abs($totalvaildbet)*100;//金额 单位分
	$notifyUrl    = $row['pay_domain']."/pay/zesheng/dfnotify_url.php";
	$url1         = 'http://expand.clpayment.com/payment/payment.do';
// 参与签名字段
$sign_fields1 = Array(
    "bankCode",
    "bankName",
    "intoCardName",
    "intoCardNo",
    "intoCardType",
    "merchantCode",
    "nonceStr",
    "outOrderId",
    "totalAmount",
    "type"
);
$map1 = Array(
    
    "bankCode" => $bankCode,
    "bankName" => $bankName,
    "intoCardName" => $intoCardName,
    "intoCardNo" => $intoCardNo,
    "intoCardType" => $intoCardType,
    "merchantCode" => $merchantCode,
    "nonceStr" => $nonceStr,
    "outOrderId" => $outOrderId,
    "totalAmount" => $totalAmount,
    "type" => $type
);


$sign0 = sign_mac($sign_fields1, $map1, $md5Key);
// 将小写字母转成大写字母
$sign1 = strtoupper($sign0);


// 使用方法
$post_data1 = array(
    'bankCode' => $bankCode,
    'bankName' => $bankName,
    'intoCardName' => $intoCardName,
    'intoCardNo' => $intoCardNo,
    'intoCardType' => $intoCardType,
    'merchantCode' =>$merchantCode,
    'nonceStr' => $nonceStr,
    'outOrderId' => $outOrderId,
    'totalAmount' => $totalAmount,
    'type' => $type,
    'remark' => $remark,
    'sign' => $sign1,
	'notifyUrl'  => $notifyUrl
);

$res = send_post($url1, $post_data1);

$resdata = json_decode($res,true);
if($resdata['code']=='00'){
    $retMsg = array('msg'=>'代付请求成功，具体情况请查询第三方商户','code'=>$resdata['code']);
	//update_success($m_id,$pay_type.'_df');
    echo json_encode($retMsg);
    exit;
}else if($resdata['code']=='30014'){
    $retMsg = array('msg'=>'非法访问ip','code'=>$resdata['code']);
	$aaa=update_error($m_id);
    echo json_encode($retMsg);
    exit;
}else if($resdata['code']=='09007'){
    $retMsg = array('msg'=>'请求报文异常','code'=>$resdata['code']);
	//update_error($m_id);
    echo json_encode($retMsg);
    exit;
}else if($resdata['code']=='09003'){
    $retMsg = array('msg'=>'验签失败','code'=>$resdata['code']);
	//update_error($m_id);
    echo json_encode($retMsg);
    exit;
}

/*发送数据  */
function send_post($url, $post_data)
{
    $postdata = http_build_query($post_data);
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 15 * 60
        ) // 超时时间（单位:s）

    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}

/* 构建签名原文 */
function sign_src($sign_fields, $map, $md5_key)
{
    // 排序-字段顺序
    sort($sign_fields);
    $sign_src = "";
    foreach ($sign_fields as $field) {
        $sign_src .= $field . "=" . $map[$field] . "&";
    }
    $sign_src .= "KEY=" . $md5_key;
    
    return $sign_src;
}

/**
 * 计算md5签名  返回的是小写的，后面需转大写
 */
function sign_mac($sign_fields, $map, $md5_key)
{
    $sign_src = sign_src($sign_fields, $map, $md5_key);
    return md5($sign_src);
}
?>
