<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "lib/Pay.Api.php";
require_once 'lib/Pay.Notify.php';
require_once 'tools/log.php';

//初始化日志
$logHandler= new CLogFileHandler("logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends PayNotify
{
	
	//重写回调处理函数
	public function NotifyCallBack($data)
	{
		Log::DEBUG("call back:" . json_encode($data));
		//处理业务
		
		//业务完成返回
		return true;
	}
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$result = $notify->Handle(true);
if($result==false){
	Log::DEBUG("notify=>验签失败!");
}
Log::DEBUG("end notify");
