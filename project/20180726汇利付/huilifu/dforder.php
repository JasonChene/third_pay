<?php session_start(); ?>
<? header("content-Type: text/html; charset=UTF-8");?>
<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");
//$top_uid = $_REQUEST['top_uid'];
if(function_exists("date_default_timezone_set"))
{
	date_default_timezone_set("Asia/Shanghai");
}

//获取第三方的资料
$params = array(':m_order'=>$_REQUEST['m_order']);
$sql = "select uid,m_value,m_make_time from k_money where m_order=:m_order and type='2'";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$uid = $row['uid'];
$m_value = $row['m_value'];
$m_make_time = $row['m_make_time'];
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
$idarray = explode("###", $pay_account);
$pu_key = $idarray[0];
$pr_key = $idarray[1];
$pay_type = $row['pay_type'];
if($pay_mid == "" || $pay_mkey == "")
{
    $retMsg = array('msg'=>'非法提交参数');
    echo json_encode($retMsg);
    exit;
}

$m_order = $_REQUEST['m_order'];

$url = 'http://agent.nta434.cn/lh_daifu/query';
$data = array(
  "mch_id" => $pay_mid, //商户号
  "out_trade_no" => $m_order//订单
);
ksort($data);
#curl获取响应值

$data = json_encode($data);
$res = send_post($url,$data);
$resdata = json_decode($res,true);

	if($resdata['status']=='1'){
		$retMsg = array('msg'=>'代付成功','code'=>'00');
		echo json_encode($retMsg);
		exit;
	}else if($resdata['remitResult']=='2'){
		$retMsg = array('msg'=>'代付中','code'=>'01');
		echo json_encode($retMsg);
		exit;
	}else if($resdata['remitResult']=='3'){
		$retMsg = array('msg'=>'代付失败','code'=>'02');
		echo json_encode($retMsg);
		exit;
	}else if($resdata['remitResult']=='0'){
		$retMsg = array('msg'=>'未找到订单','code'=>'03');
		echo json_encode($retMsg);
		exit;
	}else{
		$retMsg = array('msg'=>'查询失败','code'=>'04');
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
	$signtext = '';
	foreach ($map as $arr_key => $arr_val) {
		if (!empty($arr_val) )  {
			$signtext .= $arr_key.'='.$arr_val.'&';
		}
	}
    $signtext = $signtext .'key='.$md5_key;
    
    return $signtext;
}
?>
