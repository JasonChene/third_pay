<?php
/**
 * 
 * 回调基础类
 *
 */
 class PayNotify extends PayDataBase{
 	/**
	 * 
	 * 回调入口
	 * @param bool $needSign  是否需要验证签名
	 */
	final public function Handle($needSign = true)
	{
		//当返回false的时候，表示notify中调用NotifyCallBack回调失败获取签名校验失败，此时直接回复失败
		$result = PayApi::notify(array($this, 'NotifyCallBack'), $msg);
		if($result == false){
			PayApi::replyNotify("fail");
		} else {
			//该分支在成功回调到NotifyCallBack方法，处理完成之后流程,用户处理自己业务即可
			
			//处理业务
			
			//返回结果
			PayApi::replyNotify("success");
		}
		return $result;
	}
	
	
	/**
	 * 
	 * notify回调方法
	 * @param array $data
	 * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
	 */
	public function NotifyCallBack($data)
	{
       //TODO 用户基础该类之后需要重写该方法，成功的时候返回true，失败返回false
		return true;
	}
	
	
 }
 

