<?php
/* *
 * 类名：MustpayNotify
 * 详细：处理Mustpay各接口通知返回
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究Mustpay接口使用，只是提供一个参考

 *************************注意*************************
 * 调试通知返回时，可查看或改写log日志的写入TXT里的数据，来检查通知返回是否正常
 */

require_once("MustpayCore.function.php");
require_once("MustpayRsa.function.php");

class MustpayNotify {

	var $mustpay_config;

	function __construct($mustpay_config){
		$this->alipay_config = $mustpay_config;
	}
    /**
     * @return 验证结果
     */
	function verifyNotify(){
		if(empty($_POST)) {//判断POST来的数组是否为空
			return false;
		}else {
			//生成签名结果
			$isSign = $this->getSignVeryfy($_POST, $_POST["sign"]);
			
			//写日志记录
/*			if ($isSign) {
				$isSignStr = 'true';
			} else {
				$isSignStr = 'false';
			}
			$log_text = "notify_url_log:isSign=".$isSignStr.",";
			$log_text = $log_text.createLinkString($_POST);
			logResult($log_text);*/
			
			//验证
			//isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
			if ($isSign) {
				return true;
			} else {
				return false;
			}
		}
	}
	
    /**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @return 签名验证结果
     */
	function getSignVeryfy($para_temp, $sign) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = paraFilter($para_temp);
		
		//对待签名参数数组排序
		$para_sort = argSort($para_filter);
		
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = createLinkstring($para_sort);
		
		$isSgin = false;
		switch (strtoupper(trim($this->alipay_config['sign_type']))) {
			case "RSA" :
				$isSgin = rsaVerify($prestr, trim($this->alipay_config['plate_public_key']), $sign);
				break;
			default :
				$isSgin = false;
		}
		
		return $isSgin;
	}
}
?>
