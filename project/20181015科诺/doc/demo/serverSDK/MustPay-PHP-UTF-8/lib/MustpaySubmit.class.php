<?php
/**
 * 类名：MustpaySubmit
 * 功能：MustPay核心公用函数类
 * 详细：该类是请求、通知返回两个文件所调用的公用函数核心处理文件，不需要修改
 * 说明：以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究MustPay接口使用，只是提供一个参考。
 */
require_once("MustpayCore.function.php");
require_once("MustpayRsa.function.php");

class MustpaySubmit {

	var $mustpay_config;

	function __construct($mustpay_config){
		$this->mustpay_config = $mustpay_config;
	}

	/**
	 * 生成签名结果
	 * @param $para_sort 已排序要签名的数组
	 * return 签名结果字符串
	 */
	function buildRequestMysign($para_sort) {
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = createLinkstring($para_sort);

		$mysign = "";
		switch (strtoupper(trim($this->mustpay_config['sign_type']))) {
			case "RSA" :
				$mysign = rsaSign($prestr, $this->mustpay_config['mer_private_key']);
				break;
			default :
				$mysign = "";
		}

		return $mysign;
	}

	/**
     * 生成要请求给服务器的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
	function buildRequestPara($para_temp) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = paraFilter($para_temp);

		//对待签名参数数组排序
		$para_sort = argSort($para_filter);

		//生成签名结果
		$mysign = $this->buildRequestMysign($para_sort);

		//签名结果与签名方式加入请求提交参数组中
		$para_sort['sign'] = $mysign;
		$para_sort['sign_type'] = strtoupper(trim($this->mustpay_config['sign_type']));

		return $para_sort;
	}

	/**
     * 生成要请求给服务器的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组字符串
     */
	function buildRequestParaToString($para_temp) {
		//待请求参数数组
		$para = $this->buildRequestPara($para_temp);

		//把参数组中所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
		$request_data = createLinkstringUrlencode($para);

		return $request_data;
	}

    /**
     * 获取预支付ID
     * @param $para_temp 请求参数数组
     * @return 预支付ID
     */
	function requestPrepayId($para_temp) {
        //待请求参数数组
        $para = $this->buildRequestPara($para_temp);

        //请求获取数据
        $response = getHttpResponsePOST(trim($this->mustpay_config['add_order_url']), trim($this->mustpay_config['cacert']), $para);
        $response = json_decode($response, true);
        if($response['status'] == 1){

            //获取成功$prepayId
            $prepayId = $response['info']['prepay_id'];
        }else{
            //打印错误信息
            print_r("-----------------------------------".$response['msg']);exit;
        }

        return $prepayId;
	}

	/**
     * 获取查询的订单信息
     * @param $para_temp 请求参数数组
     * @return 订单信息
	*/
    function queryOrder($para_temp) {
        //待请求参数数组
        $para = $this->buildRequestPara($para_temp);

        //请求获取数据
        $response = getHttpResponsePOST(trim($this->mustpay_config['query_order_url']), trim($this->mustpay_config['cacert']), $para);
        $response = json_decode($response, true);
        if($response['status'] == 1){

            //获取成功
            $info = $response['info'];
        }else{
            //打印错误信息
            print_r("-----------------------------------".$response['msg']);exit;
        }

        return $info;
    }
}
?>