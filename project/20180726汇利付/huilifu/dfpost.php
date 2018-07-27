<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");
#function

if(function_exists("date_default_timezone_set"))
{
	date_default_timezone_set("Asia/Shanghai");
}
//获取订单资料
$params = array(':m_id'=>$_REQUEST['m_id'],':m_order'=>$_REQUEST['m_order']);
$sql = "select m_id,uid,m_order,totalvaildbet,m_value,pay_card,pay_num,pay_address,pay_name from k_money where m_id=:m_id and m_order=:m_order and type='2' and (df_disanfang is null or df_disanfang = '')";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$m_id = $row['m_id'];
$m_order = $row['m_order'];
$totalvaildbet = $row['totalvaildbet'];
$m_value = $row['m_value'];
$pay_card = $row['pay_card'];
$pay_num = $row['pay_num'];
$pay_address = $row['pay_address'];
$pay_name = $row['pay_name'];
$uid = $row['uid'];
//获取提款用户的层级资料
$params = array(':uid'=>$uid);
$sql = "select id,name,level_df,df_money from k_group where id in(select gid from k_user where uid=:uid)";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_id = $row['level_df'];
//获取第三方的资料
$params = array(':id'=>$pay_id);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.id=:id and is_df='1'";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
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
#固定参数设置
$bank_code = array(
'中国银行' => '1003',
'农业银行' => '1002',
'工商银行' => '1001',
'建设银行' => '1004',
'交通银行' => '1005',
'招商银行' => '1006',
'光大银行' => '1010',
'民生银行' => '1009',
'华夏银行' => '1014',
'兴业银行' => '1015',
'中信银行' => '1008',
'浦发银行' => '1012',
'邮储银行' => '1013',
'广发银行' => '1007',
'平安银行' => '1011',
'北京银行' => '1016',
'上海银行' => '1018'
);
#第三方参数设置
$m_value = abs($m_value)*100;

$data = array();
$data['mch_id']		  = $pay_mid;
$data['out_trade_no'] = $m_order;
$data['card_no']	  = $pay_num;
$data['card_name']	  = $pay_name;
$data['card_type']    = "1";
$data['bank_province']= "广东省";//开户省份
$data['bank_city']	  = "深圳市";//开户市
$data['bank_code']	  = $bank_code[$pay_card];//银行编码
$data['branch_name']  = $pay_address;//支行名称
$data['account_type'] = "2";
$data['agent_amount'] = (string)$m_value;
$data['draw_type']	  = "2";
$data['notify_url']   = $row['pay_domain']."/pay/huilifu/dfnotify_url.php";//异步
$data['once_str']	  = "jhjk1hui2he781i2y89ysdy718978y21378";

#变更参数设置

  $url ='http://agent.nta434.cn/lh_daifu/agent';
#签名排列，可自行组字串或使用http_build_query($array)
$sign0 = sign_src($data, $pay_mkey);
$sign1 = strtoupper(md5($sign0));
$data['sign']	  = $sign1;
$data['card_name']     = urlencode($data['card_name']);
$data['bank_province'] = urlencode($data['bank_province']);
$data['bank_city']	   = urlencode($data['bank_city']);
$data['branch_name']   = urlencode($data['branch_name']);
$data['notify_url']    = urlencode($data['notify_url']);

ksort($data);
$data = json_encode($data);
//echo $data;

$res = send_post($url, $data);
$resdata = json_decode($res,true);
#跳转qrcode
if($resdata['status']=='200'){
    $retMsg = array('msg'=>'代付请求成功，具体情况请查询第三方商户','code'=>'00');
	update_success($m_id,$pay_type.'_df');
    echo json_encode($retMsg);
    exit;
}else{
	$retMsg = array('msg'=>urldecode($resdata['message']),'code'=>$resdata['status']);
	update_error($m_id);
    echo json_encode($retMsg);
    exit;
}

/*发送数据  */
function send_post($url, $data)
{
      $ch = curl_init();
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
	  curl_setopt($ch, CURLOPT_POST, true);
	  curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	  curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data))
	  );
	  $tmpInfo = curl_exec($ch);
	  if (curl_errno($ch)) {
		echo(curl_errno($ch));
		exit;
	  }
	  curl_close($ch);
	  return $tmpInfo;

}

/* 构建签名原文 */
function sign_src($map, $md5_key)
{
    // 排序-字段顺序
    ksort($map);
    $sign_src = "";
    foreach ($map as $key=>$value) {
        $sign_src .= $key . "=" . $value . "&";
    }
    $sign_src = $sign_src."key=".$md5_key;
    return $sign_src;
}

?>
