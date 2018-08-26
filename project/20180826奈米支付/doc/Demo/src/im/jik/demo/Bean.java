package im.jik.demo;

public class Bean {

	/**
	 * ���� ���� ʾ��<br>
	 * account_id �̻�ID 10000<br>
	 * content_type ��ҳ���� text��json<br>
	 * thoroughfare ֧��ͨ�� �� wechat_auto<br>
	 * out_trade_no ������Ϣ 2018062101585814105<br>
	 * robin ��ѵ״̬ 2����1�ر�<br>
	 * amount ֧����� 1.00<br>
	 * callback_url �첽֪ͨurl http://pay.com/callback<br>
	 * success_url ֧���ɹ�����ת��url http://www.baidu.com<br>
	 * error_url ֧��ʧ�ܺ���ת��url http://www.baidu.com<br>
	 * sign ǩ���㷨 32λmd5// type ֧������ 1Ϊ΢�ţ�2Ϊ֧����<br>
	 * keyId �豸KEY �������Ϊ��ѵ������Ϊ�գ������ʹ�õ�һͨ�����뽫΢�Ż�֧�������豸KEY��д������
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
