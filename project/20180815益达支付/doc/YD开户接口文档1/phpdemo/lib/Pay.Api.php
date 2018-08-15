<?php


require_once "Pay.Data.php";
require_once "Pay.Config.php";
require_once "Pay.Util.php";
class PayApi {

	/**
	* 
	* 统一下单，
	* @param array $inputArry
	* @param int $timeOut
	* @throws PayException
	* @return 成功时返回，其他抛异常
	*/
	public static function unifiedOrder($inputArry, $timeOut = 30) {
		$url = PayConfig::PAY_URL;

		$inputObj = new PayDataBase();
		$inputObj->values["inputCharset"] = $inputArry['inputCharset']; //字符集
		$inputObj->values["partnerId"] = $inputArry['partnerId']; //商户号
		$inputObj->values["returnUrl"] = $inputArry['returnUrl']; //返回地址
		$inputObj->values["notifyUrl"] = $inputArry['notifyUrl']; //异步通知地址
		$inputObj->values["orderNo"] = $inputArry['orderNo']; //商户系统订单号
		$inputObj->values["orderAmount"] = $inputArry['orderAmount']; //订单金额(单位分)
		$inputObj->values["orderCurrency"] = $inputArry['orderCurrency']; //订单金额币种类型-固定值；156表示人民币
		$inputObj->values["orderDatetime"] = $inputArry['orderDatetime']; //订单提交时间
		$inputObj->values["subject"] = $inputArry['subject']; //订单标题
		$inputObj->values["body"] = $inputArry['body']; //订单描述
		$inputObj->values["signType"] = $inputArry['signType']; //签名类型
		$inputObj->values["extraCommonParam"] = $inputArry['extraCommonParam']; //扩展参数
		$inputObj->values["payMode"] = $inputArry['payMode']; //支付方式
		$inputObj->values["bnkCd"] = $inputArry['bnkCd']; //支付卡号
		$inputObj->values["cardNo"] = $inputArry['cardNo']; //银行编码
		$inputObj->values["accTyp"] = $inputArry['accTyp']; //卡类型

		//签名
		$inputObj->SetSign();

		$response = PayUtil :: postCurl($inputObj->values, $url, $timeOut);
        
        //返回结果转数组（验签）
        $result = PayResults::init($response);
		return $result;
	}
	
	/**
	 * 
	 * 查询订单，
	 * @param array $inputArry
	 * @param int $timeOut
	 * @throws PayException
	 * @return 成功时返回，其他抛异常
	 */
	public static function orderQuery($inputArry, $timeOut = 30)
	{
		$url = PayConfig::QUERY_URL;
		$inputObj = new PayDataBase();
		$inputObj->values["inputCharset"] = "1"; //字符集
		$inputObj->values["signType"] = "1"; //签名类型
		$inputObj->values["partnerId"] = $inputArry['partnerId']; //商户号
		$inputObj->values["orderNo"] = $inputArry['orderNo']; //订单号
		$inputObj->values["orderDatetime"] = $inputArry['orderDatetime']; //订单时间
		$inputObj->SetSign();//签名
		$response = PayUtil :: postCurl($inputObj->values, $url, $timeOut);
		 //返回结果转数组（验签）
        $result = PayResults::init($response);
		return $result;
	}
	
	
	/**
 	 * 
 	 * 支付结果通用通知
 	 * @param function $callback
 	 * 直接回调函数使用方法: notify(you_function);
 	 * 回调类成员函数方法:notify(array($this, you_function));
 	 * $callback  原型为：function function_name($data){}
 	 */
	public static function notify($callback, &$msg)
	{
		//获取通知的数据
		$params = file_get_contents('php://input');
		//如果返回成功则验证签名
		try {
			$result = PayResults::InitFromArray($params,true);
		} catch (PayException $e){
			$msg = $e->errorMessage();
			return false;
		}
		//验签通过调用回调函数
		return call_user_func($callback, $result);
	}
	
	
	/**
	 * 直接输出SUCCESS FAIL
	 * @param string $resCode
	 */
	public static function replyNotify($resCode)
	{
		echo $resCode;
	}
	
	
	/**
	* 
	* 代付发起，
	* @param array $inputArry
	* @param int $timeOut
	* @throws PayException
	* @return 成功时返回，其他抛异常
	*/
	public static function withdraw($inputArry, $timeOut = 30) {
		$url = PayConfig::WITHDRAW_URL;

		$inputObj = new PayDataBase();
		$inputObj->values["inputCharset"] = "1"; //字符集
		$inputObj->values["signType"] = "1"; //签名类型
		$inputObj->values["partnerId"] = $inputArry['partnerId']; //商户号
		$inputObj->values["notifyUrl"] = $inputArry['notifyUrl']; //异步通知地址
		$inputObj->values["orderNo"] = $inputArry['orderNo']; //提现订单号
		$inputObj->values["orderAmount"] = $inputArry['orderAmount']; //提现金额
		$inputObj->values["cashType"] = $inputArry['cashType']; //提现类型
		$inputObj->values["orderCurrency"] = $inputArry['orderCurrency']; //订单金额币种类型
		$inputObj->values["accountName"] = $inputArry['accountName']; //姓名
		$inputObj->values["bankName"] = $inputArry['bankName']; //银行名称
		$inputObj->values["bankCardNo"] = $inputArry['bankCardNo']; //银行卡号
		$inputObj->values["canps"] = $inputArry['canps']; //联行号
		$inputObj->values["idCard"] = $inputArry['idCard']; //身份证号
		$inputObj->values["extraCommonParam"] = $inputArry['extraCommonParam']; //公用回传参数
		

		//签名
		$inputObj->SetSign();

		$response = PayUtil :: postCurl($inputObj->values, $url, $timeOut);
        
        //返回结果转数组（验签）
        $result = PayResults::init($response);
		return $result;
	}
	
	
	/**
	* 
	* 代付订单查询，
	* @param array $inputArry
	* @param int $timeOut
	* @throws PayException
	* @return 成功时返回，其他抛异常
	*/
	public static function withdrawStatus($inputArry, $timeOut = 30) {
		$url = PayConfig::WITHDRAWSTATUS_URL;
		
		$inputObj = new PayDataBase();
		$inputObj->values["inputCharset"] = "1"; //字符集
		$inputObj->values["signType"] = "1"; //签名类型
		$inputObj->values["partnerId"] = $inputArry['partnerId']; //商户号
		$inputObj->values["orderNo"] = $inputArry['orderNo']; //订单号
		
		//签名
		$inputObj->SetSign();

		$response = PayUtil :: postCurl($inputObj->values, $url, $timeOut);
        
        //返回结果转数组（验签）
        $result = PayResults::init($response);
		return $result;
	}
	

}