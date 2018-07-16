<?php
class Pay
{
	public $orderUrl = 'http://api.payyp.com/pay/business/generate';
	public $appId = '5421A9C2F5E5BB426A7A14B5A86EA508';
	private $_appKey = '2387295305A0A666C11690F2C6FF7B5F';
	public $notifyUrl = 'https://www.example.com/callback.php';
	public $returnUrl = 'https://www.example.com';
	public $queryUrl = 'http://api.payyp.com/pay/business/querys';

	/**
	 * Ali
	 * @return string 二维码
	 */
	function order()
	{
		$data = array(
			'appId' => $this->appId,
			'money' => 1,
			'payType' => 'Ali',
			'orderNumber' => uniqid('', true),
			'notifyUrl' => $this->notifyUrl,
			'returnUrl' => $this->returnUrl,
		);
		$data['signature'] = $this->sign($data);

		return $this->request($this->orderUrl, $data);
	}

	/**
	 * 签名字符串
	 * @param $data array POST 数据
	 * @return string 签名后的字符串
	 */
	function sign($data)
	{
		$string = '';
		foreach ($data as $v) {
			$string .= $v . "&";
		};
		$string .= $this->_appKey;
		// 大写 MD5
		$signature = strtoupper(md5($string));

		return $signature;
	}

	/**
	 * @param $orderNumber string 订单号
	 * @return string JSON 字符串
	 */
	function query($orderNumber)
	{
		$data = array(
			'appId' => $this->appId,
			'orderNumber' => $orderNumber
		);
		$data['signature'] = $this->sign($data);

		return $this->request($this->queryUrl, $data);
	}

	/**
	 * @param $url string 请求地址
	 * @param $params array POST 数组
	 * @param int $timeout 超时时间
	 *
	 * @return string 返回结果
	 */
	function request($url, $params, $timeout = 8)
	{
		$curlHandle = curl_init();
		curl_setopt($curlHandle, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlHandle, CURLOPT_HEADER, true);
		curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($curlHandle, CURLOPT_POST, true);
		curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($params));
		curl_setopt($curlHandle, CURLOPT_URL, $url);

		$result = curl_exec($curlHandle);
		$info = curl_getinfo($curlHandle);
		$return = trim(substr($result, $info['header_size']));

		curl_close($curlHandle);

		return $return;
	}
}
