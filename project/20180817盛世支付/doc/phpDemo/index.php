<?php
require 'pkg8583.class.php';
//////////////////////////////////////////公共参数
$ordernum=0;
$customerId="88000002";
$merchantId="100006700000106";
$key="1234567890123457";
$requestURL="http://121.201.38.219:8088/webservice/order";

//////////////////////////////////////////操作参数
//操作类型:1.消费 2.查询 3.代付 4.代付结果查询 5.快捷支付确认
$oper=1;

//////////////////////////////////////////消费交易参数
//交易金额
$amount="20000";
$goodsname="住宿费";
//付款方式（下单与查询时保持一致）: 1.微信 2.支付宝 3.快捷支付 4.微信公众号 5.快捷支付（手机控件）6.微信主扫 7.支付宝主扫 8.QQ钱包 9.银联二维码支付 10.微信WAP
$paychannel=1;
//支付类别: 1.微信 2.支付宝 3.快捷支付 4.微信公众号 5.快捷支付（手机控件）6.微信主扫 7.支付宝主扫 8.QQ钱包 9.银联二维码支付 10.微信WAP
$paytype=1;
//微信主主扫和支付宝主扫时需要传入
$authcode="1234567890123457";
//微信WAP支付时需要传入,请更换为贵方自己的网站
$netaddress="http://www.cnghxinxi.com/";
//////////////////////////////////////////消费交易查询参数
//商户订单号（商户上传）
$merchantorderid="1000020170818063440";
//平台订单号（下单时返回）
$orderid="2016090300070024";

//////////////////////////////////////////代付交易参数
//代付到账时间，0表示T+0,1表示T+1
$settle="0";

//////////////////////////////////////////代付交易查询参数
//商户代付订单号
$paymerorder="1000020170818072057";
//平台代付订单号
$payorder="2016090300070040";

//////////////////////////////////////////快捷支付提交短信验证码参数
//快捷支付短信验证码
$bankcheckcode="830905";
$quikOrderid="2016090300070045";
//发送post请求，提交json字符串
function http_post_json($url, $jsonStr)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($jsonStr)
        )
    );
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
 
    return array($httpCode, $response);
}

function getNum()
{
    $GLOBALS['ordernum']=$GLOBALS['ordernum']+1;
    if($GLOBALS['ordernum']>=10)
        return $GLOBALS['ordernum'];
    else 
        return "0".$GLOBALS['ordernum'];
}

function testConsume()
{
    $pkg8583= new pkg8583();
    $pkg8583->setTxcode("F60002");
    //$atime=date('Y-m-d H:i:s', $time);
    $pkg8583->setTxdate(date('Ymd'));
    $pkg8583->setTxtime(date('his'));
    $pkg8583->setVersion("2.0.0");

    if($GLOBALS['paychannel']=='1')
        $pkg8583->setField003("900021");
    if($GLOBALS['paychannel']=='2')
        $pkg8583->setField003("900022");
    if($GLOBALS['paychannel']=='3')
        $pkg8583->setField003("900023");
    if($GLOBALS['paychannel']=='4')
        $pkg8583->setField003("900024");
    if($GLOBALS['paychannel']=='5')
        $pkg8583->setField003("900025");
	if($GLOBALS['paychannel']=='6')
        $pkg8583->setField003("900026");
    if($GLOBALS['paychannel']=='7')
        $pkg8583->setField003("900027");
    if($GLOBALS['paychannel']=='8')
        $pkg8583->setField003("900028");
    if($GLOBALS['paychannel']=='9')
        $pkg8583->setField003("900029");
    if($GLOBALS['paychannel']=='10')
        $pkg8583->setField003("900030");
    
    $pkg8583->setField004($GLOBALS['amount']);
    $pkg8583->setField011("000000");

	if($GLOBALS['paytype']=='1')
        $pkg8583->setField031("26005");
    if($GLOBALS['paytype']=='2')
        $pkg8583->setField031("26001");
    if($GLOBALS['paytype']=='3')
    {
        $pkg8583->setField031("26015");
        $field055="GDB|张三|1234567890123456|15907551234|CC|1234|196|123456789012345678|";
        $field055=$field055."#C|CCB|张三|123456789012345678|15907551234|123456789012345678|";
        $field055=$field055."#0.35|100|";
        $field055=mb_convert_encoding($field055, 'utf-8', 'GBK');
        $pkg8583->setField055($field055);
        
    }
    if($GLOBALS['paytype']=='4')
        $pkg8583->setField031("26030");
    if($GLOBALS['paytype']=='5')
    {
        $pkg8583->setField031("26040");
        $field055="GDB|张三|1234567890123456|15907551234|CC|1234|196|123456789012345678|";
        $field055=$field055."#C|CCB|张三|123456789012345678|15907551234|123456789012345678|";
        $field055=$field055."#0.50|40|";
        $field055=mb_convert_encoding($field055, 'utf-8', 'GBK');
        $pkg8583->setField055($field055);
    }
	if($GLOBALS['paytype']=='6')
	{
        $pkg8583->setField031("26045");
		$pkg8583->setField055($GLOBALS['authcode']);
	}
    if($GLOBALS['paytype']=='7')
	{
        $pkg8583->setField031("26050");
		$pkg8583->setField055($GLOBALS['authcode']);
	}
    if($GLOBALS['paytype']=='8')
    {
        $pkg8583->setField031("26055");        
    }
    if($GLOBALS['paytype']=='9')
        $pkg8583->setField031("26060");
    if($GLOBALS['paytype']=='10')
    {
		$pkg8583->setField011("000001");
        $pkg8583->setField031("26065");
        $pkg8583->setField055($GLOBALS['netaddress']);
    }
	
    
    $pkg8583->setField041($GLOBALS['customerId']);
    $pkg8583->setField042($GLOBALS['merchantId']);
    
    $pkg8583->setField048("10000".date("Ymdhis"));
	$pkg8583->setField057(mb_convert_encoding($GLOBALS['goodsname'], 'utf-8', 'GBK'));
    $pkg8583->setField125("123456");
    
    $signStr=$pkg8583->getSignData().$GLOBALS['key'];
    
    $signStr=mb_convert_encoding($signStr,"GBK", "utf-8");
    
    $signStr=strtoupper(md5($signStr));
    $signStr=substr($signStr, 0,16);
    
    $pkg8583->setField128($signStr);
    $jsonStr = $pkg8583->getJsonStr();    
    list($returnCode, $returnContent)=http_post_json($GLOBALS['requestURL'], $jsonStr);
    
	echo mb_convert_encoding($returnContent, 'GBK', 'utf-8');
    //echo $returnContent;
}
function queryConsumeResult()
{
    $pkg8583= new pkg8583();
    $pkg8583->setTxcode("F60004");
    //$atime=date('Y-m-d H:i:s', $time);
    $pkg8583->setTxdate(date('Ymd'));
    $pkg8583->setTxtime(date('his'));
    $pkg8583->setVersion("2.0.0");

    if($GLOBALS['paychannel']=='1')
        $pkg8583->setField003("900021");
    if($GLOBALS['paychannel']=='2')
        $pkg8583->setField003("900022");
    if($GLOBALS['paychannel']=='3')
        $pkg8583->setField003("900023");
    if($GLOBALS['paychannel']=='4')
        $pkg8583->setField003("900024");
    if($GLOBALS['paychannel']=='5')
        $pkg8583->setField003("900025");
    if($GLOBALS['paychannel']=='6')
        $pkg8583->setField003("900026");
    if($GLOBALS['paychannel']=='7')
        $pkg8583->setField003("900027");
    if($GLOBALS['paychannel']=='8')
        $pkg8583->setField003("900028");
    if($GLOBALS['paychannel']=='9')
        $pkg8583->setField003("900029");
    if($GLOBALS['paychannel']=='10')
        $pkg8583->setField003("900030");
    
    $pkg8583->setField004($GLOBALS['amount']);
    $pkg8583->setField011("000000");
    
    $pkg8583->setField041($GLOBALS['customerId']);
    $pkg8583->setField042($GLOBALS['merchantId']);
    
    $pkg8583->setField048($GLOBALS['merchantorderid']);
    $pkg8583->setField062($GLOBALS['orderid']);
    $pkg8583->setField125("123456");
    
    $signStr=$pkg8583->getSignData().$GLOBALS['key'];
    $signStr=strtoupper(md5($signStr));
    $signStr=substr($signStr, 0,16);
    
    $pkg8583->setField128($signStr);
    $jsonStr = $pkg8583->getJsonStr();    
    list($returnCode, $returnContent)=http_post_json($GLOBALS['requestURL'], $jsonStr);
    
    echo $returnContent;
}
function payForAnother()
{
    $pkg8583= new pkg8583();
    $pkg8583->setTxcode("F60007");
    //$atime=date('Y-m-d H:i:s', $time);
    $pkg8583->setTxdate(date('Ymd'));
    $pkg8583->setTxtime(date('his'));
    $pkg8583->setVersion("2.0.0");
    $pkg8583->setField003("000000");
    $pkg8583->setField004($GLOBALS['amount']);
    $pkg8583->setField011(date("md").getNum());
    $pkg8583->setField031($GLOBALS['settle']);    
    $pkg8583->setField041($GLOBALS['customerId']);
    $pkg8583->setField042($GLOBALS['merchantId']);
    
    $pkg8583->setField048("10000".date("Ymdhis"));
    $contents="张三|1234567890123456|招商银行|308290003020|15907551234|";
    $contents=mb_convert_encoding($contents, 'utf-8', 'GBK');
    $pkg8583->setField055($contents);
    
    $pkg8583->setField125("123456");
    
    $signStr=$pkg8583->getSignData().$GLOBALS['key'];
    
    $signStr=mb_convert_encoding($signStr,"GBK", "utf-8");
    $signStr=strtoupper(md5($signStr));
    $signStr=substr($signStr, 0,16);
    
    $pkg8583->setField128($signStr);
    $jsonStr = $pkg8583->getJsonStr(); 
    echo $jsonStr."\n";
    list($returnCode, $returnContent)=http_post_json($GLOBALS['requestURL'], $jsonStr);
    
    $returnContent=mb_convert_encoding($returnContent,"utf-8", "utf-8,GBK");
    echo $returnContent;
}
function queryPayFA()
{
    $pkg8583= new pkg8583();
    $pkg8583->setTxcode("F60008");
    //$atime=date('Y-m-d H:i:s', $time);
    $pkg8583->setTxdate(date('Ymd'));
    $pkg8583->setTxtime(date('his'));
    $pkg8583->setVersion("2.0.0");
    $pkg8583->setField003("000000");
    
    $pkg8583->setField011(date("md").getNum());
    
    $pkg8583->setField041($GLOBALS['customerId']);
    $pkg8583->setField042($GLOBALS['merchantId']);
    
    $pkg8583->setField062($GLOBALS['payorder']);
    $pkg8583->setField125("123456");
    
    $signStr=$pkg8583->getSignData().$GLOBALS['key'];
    $signStr=strtoupper(md5($signStr));
    $signStr=substr($signStr, 0,16);
    
    $pkg8583->setField128($signStr);
    $jsonStr = $pkg8583->getJsonStr();    
    list($returnCode, $returnContent)=http_post_json($GLOBALS['requestURL'], $jsonStr);
    
    echo $returnContent;
}
function quikPayOrderConfirm()
{
    $pkg8583= new pkg8583();
    $pkg8583->setTxcode("F60003");
    //$atime=date('Y-m-d H:i:s', $time);
    $pkg8583->setTxdate(date('Ymd'));
    $pkg8583->setTxtime(date('his'));
    $pkg8583->setVersion("2.0.0");
    $pkg8583->setField003("900023");
    
    $pkg8583->setField011(date("md").getNum());
    
    $pkg8583->setField041($GLOBALS['customerId']);
    $pkg8583->setField042($GLOBALS['merchantId']);
    $pkg8583->setField057($GLOBALS['bankcheckcode']);
    $pkg8583->setField062($GLOBALS['quikOrderid']);
    $pkg8583->setField125("123456");
    
    $signStr=$pkg8583->getSignData().$GLOBALS['key'];
    $signStr=strtoupper(md5($signStr));
    $signStr=substr($signStr, 0,16);
    
    $pkg8583->setField128($signStr);
    $jsonStr = $pkg8583->getJsonStr();    
    list($returnCode, $returnContent)=http_post_json($GLOBALS['requestURL'], $jsonStr);
    
    echo $returnContent;
}

if($GLOBALS['oper']=="1")
    testConsume();
else if($oper=='2')
    queryConsumeResult();
else if($oper=='3')
    payForAnother();
else if($oper=='4')
    queryPayFA();
else if($oper=='5')
    quikPayOrderConfirm();
else 
    echo "未定义的操作";
?>
