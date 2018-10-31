<?php
require_once ("codepay_config.php"); //导入配置文件
require_once ("includes/MysqliDb.class.php"); //导入mysqli连接
require_once ("includes/M.class.php"); //导入mysqli操作类

function createLinkstring($data){
    $sign='';
    foreach ($data AS $key => $val) {
        if ($sign) $sign .= '&'; //第一个字符串签名不加& 其他加&连接起来参数
        $sign .= $key.'='.$val; //拼接为url参数形式
    }
	return $sign;
}

$data = (array)json_decode(file_get_contents('php://input'), true);
//业务处理例子 返回一些字符串
$pay_id = $data['user_id']; //需要充值的ID 或订单号 或用户名
$money = (float)$data['money']; //实际付款金额
$price = (float)$data['money']; //订单的原价
$type = $data['type']; //支付方式
$pay_no = $data['order_no']; //支付流水号
$param = $data['trade_no']; //自定义参数 原封返回 您创建订单提交的自定义参数
$pay_time = time(); //付款时间戳
$pay_tag = 'kxzf'; //支付备注 仅支付宝才有 其他支付方式全为0或空
$status = 2; //业务处理状态 这里就全设置为2  如有必要区分是否业务同时处理了可以处理完再更新该字段为其他值
$creat_time = time(); //创建数据的时间戳
if ($money <= 0 || empty($pay_id) || empty($pay_no)) {
    echo '缺少必要的一些参数'; //测试数据中 唯一标识必须包含这些
}

//实例化mysqli 操作库 需在php.ini启用mysqli 启用方法：删除extension=php_mysqli.dll前面的 ; (分号)重启web服务器
//MYSQL用户需要拥有INSERT update权限
$m = new M();
//以下参数为不小心删除了导致无法执行做准备 没有太多实际意义 就是些初始值
if (!defined('DB_USERTABLE')) defined('DB_USERTABLE', 'codepay_user'); //默认的用户数据表
if (!defined('DB_PREFIX')) defined('DB_PREFIX', 'codepay'); //默认的表前缀
if (!defined('DB_AUTOCOMMIT')) defined('DB_AUTOCOMMIT', false); //默认使用事物 回滚
if (!defined('DEBUG')) defined('DEBUG', false); //默认启用调试模式 但这里如果读不到就不启用了

$m->db->autocommit(DB_AUTOCOMMIT); //默认不自动提交 即事物开启 只针对InnoDB引擎有效

/**
 * 插入到用户付款记录默认codepay_order表使用了2种唯一索引来区分是否已经存在。确保业务只执行一次
 * 以下为作为识别是否已经执行过此笔订单 建议保留 否则您必须确保业务已经处理
 */
$insertSQL = "INSERT INTO `" . DB_PREFIX . "_order` (`pay_id`, `money`, `price`, `type`, `pay_no`, `param`, `pay_time`, `pay_tag`, `status`, `creat_time`)values(?,?,?,?,?,?,?,?,?,?)";
$stmt = $m->prepare($insertSQL); //预编译SQL语句
if (!$stmt) {
    echo "数据表:" . DB_PREFIX . "_order  不存在 可能需要重新安装";
}
$stmt->bind_param('sddissisii', $pay_id, $money, $price, $type, $pay_no, $param, $pay_time, $pay_tag, $status, $creat_time); //防止SQL注入
$rs = $stmt->execute(); //执行SQL
if ($rs && $stmt->affected_rows >= 1) { //插入成功 是首次通知 可以执行业务
    mysqli_stmt_close($stmt); //关闭上次的预编译
    //加钱
	$sql="update fn_user set money=money+{$money} where userid=?";
    $stmt = $m->prepare($sql);
    $stmt->bind_param('s', $pay_id); //$pay_id 为您传递的参数 可以是用户ID 用户名 订单号。
    if (empty($stmt)) echo sprintf("SQL语句存在问题一般是参数修改不正确造成   SQL: %s 参数：%s ", $sql, createLinkstring($data));
    if ($stmt->error != '') { //捕获错误 这一般是数据表不存在造成
        $result = sprintf("数据表存在问题 ：%s SQL: %s 参数：%s ", $stmt->error, $sql, createLinkstring($data));
        mysqli_stmt_close($stmt); //关闭预编译
        $m->rollback(); //回滚
        echo $result;
    }
    $stmt->bind_param('s', $pay_id); //绑定参数 防止注入
    $rs = $stmt->execute(); //执行SQL语句
	
    if ($rs && $stmt->affected_rows >= 1) {
		echo 'success';
        if (!DB_AUTOCOMMIT){
			$m->db->commit(); //提交事物 保存数据
		}
    } else { //如果下次还要处理则应该开启事物 数据库引擎为InnoDB 不支持事物该笔订单是无法再执行到业务处理这个步骤除非是使用订单状态标识区分
        if ($error_msg == '' && $stmt->affected_rows <= 0) {
            $error_msg = '该用户可能不存在 请核对 如果默认的演示只存在admin用户 需要你更改codepay_config.php 最下面3个参数为你的用户表信息';
        }
        $result = sprintf("业务处理失败了 ：%s SQL: %s 参数：%s ", $error_msg, $sql, createLinkstring($data));
        $m->rollback(); //回滚
		echo $result;
    }
} else if ($stmt->errno == 1062) {
    echo '订单已处理,不要重复回调';
    //已经存在 表示已经执行过 直接返回ok或success 不要再通知了.
    //如果不支持事物 就算之前执行失败了也是直接返回成功。
    
} else {
    $m->rollback(); //错误回滚
    if ($stmt->errno == 1146) { //不存在测试数据表
        $result = '您还未安装测试数据 无法使用业务处理示范'; //需在网页执行 install.php 安装测试数据 如访问：http://您的网站/codepay/install.php
        
    } else {
        $result = sprintf("比较严重的错误必须处理 ：%s SQL: %s 参数：%s \r\nMYSQL信息：%s", $stmt->error, $insertSQL, createLinkstring($data), createLinkstring($stmt));
    }
}
mysqli_stmt_close($stmt); //关闭预编译
echo $result;
?>

