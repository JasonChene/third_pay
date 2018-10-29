package im.jik.demo;

public class Bean {

	/**
	 * 参数 作用 示例<br>
	 * account_id 商户ID 10000<br>
	 * content_type 网页类型 text或json<br>
	 * thoroughfare 支付通道 如 wechat_auto<br>
	 * out_trade_no 订单信息 2018062101585814105<br>
	 * robin 轮训状态 2开启1关闭<br>
	 * amount 支付金额 1.00<br>
	 * callback_url 异步通知url http://pay.com/callback<br>
	 * success_url 支付成功后跳转到url http://www.baidu.com<br>
	 * error_url 支付失败后跳转到url http://www.baidu.com<br>
	 * sign 签名算法 32位md5// type 支付类型 1为微信，2为支付宝<br>
	 * keyId 设备KEY 如果请求为轮训，该项为空，如果想使用单一通道，请将微信或支付宝的设备KEY填写至这里
	 */

	private String account_id;
	private String content_type;
	private String thoroughfare;
	private String out_trade_no;
	private String robin;
	private String amount;
	private String callback_url;
	private String success_url;
	private String error_url;
	private String sign;
	private String type;
	private String keyId;

	public String getType() {
		return type;
	}

	public void setType(String type) {
		this.type = type;
	}

	public String getAccount_id() {
		return account_id;
	}

	public void setAccount_id(String account_id) {
		this.account_id = account_id;
	}

	public String getContent_type() {
		return content_type;
	}

	public void setContent_type(String content_type) {
		this.content_type = content_type;
	}

	public String getThoroughfare() {
		return thoroughfare;
	}

	public void setThoroughfare(String thoroughfare) {
		this.thoroughfare = thoroughfare;
	}

	public String getOut_trade_no() {
		return out_trade_no;
	}

	public void setOut_trade_no(String out_trade_no) {
		this.out_trade_no = out_trade_no;
	}

	public String getRobin() {
		return robin;
	}

	public void setRobin(String robin) {
		this.robin = robin;
	}

	public String getAmount() {
		return amount;
	}

	public void setAmount(String amount) {
		this.amount = amount;
	}

	public String getCallback_url() {
		return callback_url;
	}

	public void setCallback_url(String callback_url) {
		this.callback_url = callback_url;
	}

	public String getSuccess_url() {
		return success_url;
	}

	public void setSuccess_url(String success_url) {
		this.success_url = success_url;
	}

	public String getError_url() {
		return error_url;
	}

	public void setError_url(String error_url) {
		this.error_url = error_url;
	}

	public String getSign() {
		return sign;
	}

	public void setSign(String sign) {
		this.sign = sign;
	}

	public String getKeyId() {
		return keyId;
	}

	public void setKeyId(String keyId) {
		this.keyId = keyId;
	}

}
