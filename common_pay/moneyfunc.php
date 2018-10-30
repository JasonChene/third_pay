<?php
function insert_online_money($username,$m_order,$m_value,$bankname,$payType="system",$top_uid=0) {
	global $mydata1_db;
	/**
	 * 因 在线提交页面未包含 common 或config 文件，读取数据库获取在线支付赠送；
	 */
	$sql	=	"select * from cdb_options where option_name='web_site'";
	$query	=	$mydata1_db->query($sql);
	$rows	=	$query->fetch();
	$web_site = unserialize($rows['option_value']);
	$default_ckzs = $web_site['default_ckzs'];
	$params = array(':username'=>$username);
	$sql = "select uid,username,money from k_user where username=:username limit 1";
	$stmt = $mydata1_db->prepare($sql);
	$stmt->execute($params);
	$rows = $stmt->fetch();
	$cou = $stmt->rowCount();
	if ($cou<=0) {
		return -1; //会员信息不存在，无法入账！
	}
	$assets = $rows['money'];
	$uid = $rows['uid'];
	$zsjr	= round($m_value/100*$default_ckzs,2);
	
	$params = array(':m_order'=>$m_order);
	$sql = "select m_id from k_money where m_order=:m_order";
	$stmt = $mydata1_db->prepare($sql);
	$stmt->execute($params);
	$cou = $stmt->rowCount();
	if ($cou==0) {
		$params = array(
			':uid'=>$uid,
			':m_value'=>$m_value,
			':m_order'=>$m_order,
			':assets'=>$assets,
			':zsjr' =>$zsjr,
			':balance'=>$assets+$m_value+$zsjr,':operator'=>$payType,':top_uid'=>$top_uid);
		$sql = "
			insert into k_money (
				uid,
				m_value,
				m_order,
				status,
				assets,
				balance,
				type,
				zsjr,operator,top_uid
			) values (
				:uid,
				:m_value,
				:m_order,
				2,
				:assets,
				:balance,
				1,:zsjr,:operator,:top_uid)";
		$stmt = $mydata1_db->prepare($sql);
		$stmt->execute($params);
		$q1 = $stmt->rowCount();
		$m_id = $mydata1_db->lastInsertId();
		
		$params = array(':m_id'=>$m_id,':about'=>$bankname);
		$sql = "
			update k_money,k_user set 
				k_money.status=1,
				k_money.update_time=now(),
				k_user.money=k_user.money+k_money.m_value+k_money.zsjr,
				k_money.about=:about,
				k_money.sxf=k_money.m_value/100
			where k_money.uid=k_user.uid 
			and k_money.m_id=:m_id 
			and k_money.`status`=2";
		$stmt = $mydata1_db->prepare($sql);
		$stmt->execute($params);
		$q2 = $stmt->rowCount();
		
		/* 记录额度日志 2014.09.15 开始 */
		$creationTime = date("Y-m-d H:i:s");
		$params = array(':creationTime'=>$creationTime,':m_id'=>$m_id);
		$sql = "
			INSERT INTO k_money_log (
				uid,
				userName,
				gameType,
				transferType,
				transferOrder,
				transferAmount,
				previousAmount,
				currentAmount,
				creationTime) 
			SELECT 
				k_user.uid,
				k_user.username,
				'ONLINEPAY',
				'IN',
				k_money.m_order,
				k_money.m_value+k_money.zsjr,
				k_user.money-k_money.m_value-k_money.zsjr,
				k_user.money,
				:creationTime 
			FROM k_user,k_money 
			WHERE k_user.uid=k_money.uid 
			AND k_money.status=1 
			AND k_money.m_id=:m_id";
		$stmt = $mydata1_db->prepare($sql);
		$stmt->execute($params);
		$q3 = $stmt->rowCount();
		/* 记录额度日志 2014.09.15 结束 */
		
		if ($q1 && $q2 && $q3) {
			return 1; //支付成功！
		} else {
			return -2; //数据库操作失败！
		}
	} else {
		return 0; //会员已经入账，无需重复入账！
	}
}

function insert_online_order($username,$m_order,$m_value,$bankname,$payType="system",$top_uid=0) {
	global $mydata1_db;	
	/**
	 * 因 在线提交页面未包含 common 或config 文件，读取数据库获取在线支付赠送；
	 */
	$sql	=	"select * from cdb_options where option_name='web_site'";
	$query	=	$mydata1_db->query($sql);
	$rows	=	$query->fetch();
	$web_site = unserialize($rows['option_value']);
	$default_ckzs = $web_site['default_ckzs'];
	$params = array(':username'=>$username);
	$sql = "select uid,username,money from k_user where username=:username limit 1";
	$stmt = $mydata1_db->prepare($sql);
	
	$stmt->execute($params);
	$rows = $stmt->fetch();
	$cou = $stmt->rowCount();
	if ($cou<=0) {
		return -1; //会员信息不存在，无法入账！
	}
	$assets = $rows['money'];
	$uid = $rows['uid'];
	$zsjr	= round($m_value/100*$default_ckzs,2);
	$params = array(':m_order'=>$m_order);
	$sql = "select m_id from k_money where m_order=:m_order";
	$stmt = $mydata1_db->prepare($sql);
	$stmt->execute($params);
	$cou = $stmt->rowCount();
	if ($cou==0) {
		$params = array(
			':uid'=>$uid,
			':m_value'=>$m_value,
			':m_order'=>$m_order,
			':assets'=>$assets,
			':zsjr' =>$zsjr,
			':balance'=>$assets+$m_value+$zsjr,
			':about'=>$bankname,':operator'=>$payType,':top_uid'=>$top_uid);
		$sql = "
			insert into k_money (
				uid,
				m_value,
				m_order,
				status,
				assets,
				balance,
				type,
				zsjr,
				about,operator,top_uid
			) values (
				:uid,
				:m_value,
				:m_order,
				2,
				:assets,
				:balance,
				1,:zsjr,:about,:operator,:top_uid)";
		$stmt = $mydata1_db->prepare($sql);
		$stmt->execute($params);

	} else {
		return -2; //订单号已存在，请返回支付页面重新支付！
	}
}

function update_online_money($m_order,$m_value) {
	global $mydata1_db;
	/**
	 * 因 在线提交页面未包含 common 或config 文件，读取数据库获取在线支付赠送；
	 */
	$sql	=	"select * from cdb_options where option_name='web_site'";
	$query	=	$mydata1_db->query($sql);
	$rows	=	$query->fetch();
	$web_site = unserialize($rows['option_value']);
	$default_ckzs = $web_site['default_ckzs'];
	$params = array(':m_order'=>$m_order);
	$sql = "select m_id , status from k_money where m_order=:m_order";
	$stmt = $mydata1_db->prepare($sql);
	$stmt->execute($params);
	$rows = $stmt->fetch();
	$m_id = $rows['m_id'];
	$status = $rows['status'];
	$zsjr	= round($m_value/100*$default_ckzs,2);
	$money = round($m_value,2);
	$cou = $stmt->rowCount();
	if ($cou==1 && $status == 2) {		
		$params = array(':m_id'=>$m_id,':m_value'=>$money,':zsjr'=>$zsjr);
		$sql = "
			update k_money,k_user set 
				k_money.status=1,
				k_money.update_time=now(),
				k_money.m_value=:m_value,
				k_user.money=k_user.money+:m_value+:zsjr,
				k_money.assets=k_user.money,
				k_money.sxf=k_money.m_value/100,
				k_money.balance=k_user.money+:m_value+:zsjr 
			where k_money.uid=k_user.uid 
			and k_money.m_id=:m_id 
			and k_money.`status`=2";
		$stmt = $mydata1_db->prepare($sql);
		$stmt->execute($params);
		$q2 = $stmt->rowCount();
		
		/* 记录额度日志 2014.09.15 开始 */
		$creationTime = date("Y-m-d H:i:s");
		$params = array(':creationTime'=>$creationTime,':m_id'=>$m_id);
		$sql = "
			INSERT INTO k_money_log (
				uid,
				userName,
				gameType,
				transferType,
				transferOrder,
				transferAmount,
				previousAmount,
				currentAmount,
				creationTime) 
			SELECT 
				k_user.uid,
				k_user.username,
				'ONLINEPAY',
				'IN',
				k_money.m_order,
				k_money.m_value+k_money.zsjr,
				k_user.money-k_money.m_value-k_money.zsjr,
				k_user.money,
				:creationTime 
			FROM k_user,k_money 
			WHERE k_user.uid=k_money.uid 
			AND k_money.status=1 
			AND k_money.m_id=:m_id";
		$stmt = $mydata1_db->prepare($sql);
		$stmt->execute($params);
		$q3 = $stmt->rowCount();
		/* 记录额度日志 2014.09.15 结束 */
		
		if ($q2 && $q3) {
			return 1; //支付成功！
		} else {
			return -2; //数据库操作失败！
		}
	} else {
		return 0; //会员已经入账，无需重复入账！
	}
}

function check_user_login($uid,$username) {
	global $mydata1_db;
	
	$params = array(':uid'=>$uid,':username'=>$username);
	$sql = "select uid,username,mobile,money from k_user where uid=:uid and username=:username";
	$stmt = $mydata1_db->prepare($sql);
	$stmt->execute($params);
	$row = $stmt->fetch();
	
	return $row;
}

function write_log($str){ #输出LOG日志
	$str = date('Y-m-d H:i:s') . ' '  . $str . "\r\n";
	file_put_contents(date('Ymd',time()).".log", $str,FILE_APPEND);
}
//获取客户端IP
function getClientIp() {
	$ip = $_SERVER['REMOTE_ADDR'];
		
	if (isset($_SERVER['HTTP_X_REAL_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_REAL_FORWARDED_FOR'];
	} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		$aa = explode(",",$ip);
		$ip = $aa[0];
	} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
		
	return $ip;
}
//商家定单号(必填)
function getOrderNo(){
	return rand(100000,999999)."".date("YmdHis");
}
function _is_mobile() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
    $is_mobile = false;
    foreach ($mobile_agents as $device) {
        if (stristr($user_agent, $device)) {
            $is_mobile = true;
            break;
        }
    }
    return $is_mobile;
}

//代付提交成功
function update_success($m_id,$pay_type){
	global $mydata1_db;
	$params = array(':m_id'=>$m_id,':df_disanfang'=>$pay_type);
	$sql = "update k_money set df_disanfang=:df_disanfang where m_id=:m_id";
	$stmt = $mydata1_db->prepare($sql);
	$stmt->execute($params);
	$cou = $stmt->rowCount();
	return $cou;
}
//代付提交失败
function update_error($m_id){
	global $mydata1_db;
	$params = array(':m_id'=>$m_id);
	$sql = "update k_money set df_disanfang=null,operator='system',operatorid='',operatstatus='0' where m_id=:m_id";
	$stmt = $mydata1_db->prepare($sql);
	$stmt->execute($params);
	$cou = $stmt->rowCount();
	return $cou;
}
//代付异步处理
function update_orderstatus($m_order,$about,$sxf){
	global $mydata1_db;
	$params = array(':m_order'=>$m_order,':about'=>$about,':sxf'=>$sxf);
	$sql = "update k_money set status='1',about=:about,sxf=:sxf,update_time=now() where m_order=:m_order and type='2'";
	$stmt = $mydata1_db->prepare($sql);
	$stmt->execute($params);
	$q2 = $stmt->rowCount();
	if($q2){
		return 1;//修改成功
	}else {
			return -2; //数据库操作失败！
		}
}
?>
