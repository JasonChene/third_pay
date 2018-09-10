<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

function sign_src($map, $md5_key)
{
    // 排序-字段顺序
    ksort($map);
    $sign_src = "";
    foreach ($map as $key=>$value) {
		if($key!="sign"){
        $sign_src .= $key . "=" . $value . "&";
		}
    }
    $sign_src = $sign_src."key=".$md5_key;
    return $sign_src;
}

$getdata = file_get_contents("php://input");
$data	 = json_decode($getdata,true);
$m_order = $data['out_trade_no'];
//write_log("orderNo==".$data['out_trade_no']);
// foreach ($data as $key11 => $value11) {
//   write_log($key11."=".$value11);
// }

//获取第三方的资料
$params = array(':m_order'=>$m_order);
$sql = "select uid,m_value,m_make_time,about,m_id,status from k_money where m_order=:m_order and type='2'";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$uid = $row['uid'];
$m_value = $row['m_value'];
$m_make_time = $row['m_make_time'];
$m_id = $row['m_id'];
$status = $row['status'];
$about = $row['about']."汇利付代付_df";

//write_log("uid==".$uid);
//获取提款用户的层级资料
$params = array(':uid'=>$uid);
$sql = "select id,name,level_df,df_money from k_group where id in(select gid from k_user where uid=:uid)";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_id = $row['level_df'];
//write_log("pay_id==".$pay_id);
//获取第三方的资料
$params = array(':id'=>$pay_id);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.id=:id and is_df='1'";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];

//write_log("m_id==".$m_id);

//write_log("pay_mkey==".$pay_mkey);

//write_log("pay_mid==".$pay_mid);
if($m_id == "" || $pay_mkey == ""|| $pay_mid == "")
{
	echo "非法提交参数";
	exit;
}
if($status == "1")
{
	echo "订单已支付成功";
	exit;
}

$sign0 = sign_src($data, $pay_mkey);
$mysign = strtoupper(md5($sign0));
//write_log("status".$data['status']);
//write_log("sign".$data['sign']);
//write_log("mysign".$mysign);

if ($data['status'] == "1") {

		$result_insert = update_orderstatus($m_order,$about,'0');
		//write_log("result_insert==".$result_insert);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");	
			exit;
		}else if($result_insert == 0){
			echo ("ok");
			// write_log("SUCCESS");
			exit;
		}else if($result_insert == -2){
			echo ("数据库操作失败");
			exit;
		}else if($result_insert == 1){
			echo ("ok");
			// write_log("SUCCESS");
			exit;
		} else {
			echo ("支付失败");
			exit;
		}

}else{
	echo ("交易失败");
	exit;
}

?>
