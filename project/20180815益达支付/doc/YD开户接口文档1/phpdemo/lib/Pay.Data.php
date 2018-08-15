<?php

require_once "Pay.Config.php";
require_once "Pay.Exception.php";
require_once "/tools/Handle_RSA.php";
/**
 * 
 * 数据对象基础类，该类中定义数据类最基本的行为，包括：
 * 计算/设置/获取签名、输出json格式的参数、从json读取数据对象等
 *
 */
class PayDataBase {
	public $values = array ();

	/**
	* 设置签名，详见签名生成算法
	* @param string $value 
	**/
	public function SetSign() {
		//用商户私钥加密
		$sign = $this->MakeSign(PayConfig::MCH_PRIVATE_KEY);
		$this->values['signMsg'] = $sign;
		return $sign;
	}

	/**
	* 获取签名，详见签名生成算法的值
	* @return 值
	**/
	public function GetSign() {
		return $this->values['signMsg'];
	}

	/**
	* 判断签名，详见签名生成算法是否存在
	* @return true 或 false
	**/
	public function IsSignSet() {
		return array_key_exists('signMsg', $this->values);
	}

	/**
	 * 输出xml字符
	 * @throws PayException
	**/
	public function ToXml() {
		if (!is_array($this->values) || count($this->values) <= 0) {
			throw new PayException("数组数据异常！");
		}

		$xml = "<xml>";
		foreach ($this->values as $key => $val) {
			if (is_numeric($val)) {
				$xml .= "<" . $key . ">" . $val . "</" . $key . ">";
			} else {
				$xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
			}
		}
		$xml .= "</xml>";
		return $xml;
	}

	/**
	 * 将xml转为array
	 * @param string $xml
	 * @throws PayException
	 */
	public function FromXml($xml) {
		if (!$xml) {
			throw new PayException("xml数据异常！");
		}
		//将XML转为array
		//禁止引用外部xml实体
		libxml_disable_entity_loader(true);
		$this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $this->values;
	}
	
	
	/**
	 * 将json转为array
	 * @param string $jsonStr
	 * @throws PayException
	 */
	public function FromJson($jsonStr) {
		if (!$jsonStr) {
			throw new PayException("json数据异常！");
		}
	
		$this->values = json_decode($jsonStr, true);
		return $this->values;
	}
	

	/**
	 * 格式化参数格式化成url参数
	 */
	public function ToUrlParams() {
		$buff = "";
		foreach ($this->values as $k => $v) {
			if ($k != "SIGN_DAT" && $v != "" && !is_array($v)) {
				$buff .= $k . "=" . $v . "&";
			}
		}

		$buff = trim($buff, "&");
		return $buff;
	}

	/**
	 * 生成签名
	 * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
	 */
	public function MakeSign($key) {
		$signType = $this->values['signType'];
		unset($this->values['signType']);//此参数不参与签名
		//签名步骤一：按字典序排序参数
		ksort($this->values);
		//签名步骤二：组装字符串
		$signStr = $this->ToUrlParams();
		//签名步骤三：用密钥加密
		$rsa = new Handle_RSA();
		$result = $rsa->get_sign($signStr,$key);
		//签名完成后写回参数
		$this->values['signType'] = $signType;
		return $result;
	}

	/**
	 * 获取设置的值
	 */
	public function GetValues() {
		return $this->values;
	}
}

/**
 * 
 * 接口调用结果类
 *
 */
class PayResults extends PayDataBase
{
	/**
	 * 
	 * 检测签名
	 */
	public function CheckSign()
	{
		//fix异常
		if(!$this->IsSignSet()){
			throw new PayException("签名错误！");
		}
		//signMsg,signType不参与验签
		$signMsg = $this->values['signMsg'];
		unset($this->values['signMsg']);
		$signType = $this->values['signType'];
		unset($this->values['signType']);
		//签名步骤一：按字典序排序参数
		ksort($this->values);
		//签名步骤二：组装字符串
		$signStr = $this->ToUrlParams();
		//签名步骤三：用密钥加密
		$rsa = new Handle_RSA();
		//用平台公钥验签名
		$result = $rsa->verity($signStr,$signMsg,PayConfig::PUBLIC_KEY);
		//回填参数
		$this->values['signMsg'] = $signMsg;
		$this->values['signType'] = $signType;
		if($result){
			return true;
		}
		throw new PayException("签名错误！");
	}
	
	/**
	 * 
	 * 使用数组初始化
	 * @param array $array
	 */
	public function FromArray($array)
	{
		$this->values = $array;
	}
	
	/**
	 * 
	 * 使用数组初始化对象
	 * @param array $array
	 * @param 是否检测签名 $noCheckSign
	 */
	public static function InitFromArray($array, $noCheckSign = false)
	{
		$obj = new self();
		$obj->FromArray($array);
		if($noCheckSign == false){
			$obj->CheckSign();
		}
        return $obj;
	}
	
	/**
	 * 
	 * 设置参数
	 * @param string $key
	 * @param string $value
	 */
	public function SetData($key, $value)
	{
		$this->values[$key] = $value;
	}
	
    /**
     * 将json转为array
     * @param string $jsonStr
     * @param 是否检测签名 $noCheckSign
     * @throws PayException
     */
	public static function Init($jsonStr, $noCheckSign = false)
	{	
		$obj = new self();
		$obj->FromJson($jsonStr);
		if(array_key_exists("errCode", $obj->values) && $obj->values['errCode'] != '0000'){
			 return $obj->GetValues();
		}
		if($noCheckSign == false){
			$obj->CheckSign();
		}
        return $obj->GetValues();
	}
}

