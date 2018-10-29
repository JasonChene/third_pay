package im.jik.demo;

import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

import com.alibaba.fastjson.JSONObject;

import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;

public class Demo {
	private static final String CALLBACK_URL = "http://47.105.34.228/";//回调地址
	private static final String SUCCESS_URL = "http://47.105.34.228/";//成功支付后地址
	private static final String ERROT_URL = "http://47.105.34.228/";
	private static final String KEY = "B2141B8321111A";//商户后台S_KEY
	private static OkHttpClient client = new OkHttpClient();

	public static void main(String[] args) {
		HashMap<String, String> params = new HashMap<>();
		String price = "500.00"; //用户支付的金额   传过来的参数必须为String 且 格式为 1000.00含两位小数   单笔金额<=50000.00的
		params.put("account_id", "10008");//S_KEY->商户KEY，到平台首页自行复制粘贴，该参数无需上传，用来做签名验证和回调验证，请勿泄露
		params.put("content_type", "json");// //请求获取的网页类型，json 返回json数据，text直接跳转html界面支付，如没有特殊需要，建议默认text即可
		params.put("thoroughfare", "alipay_auto");//支付通道：支付宝（公开版）：alipay_auto、微信（公开版）：wechat_auto
		params.put("out_trade_no", "201806261212440");//订单号码->这个是四方网站发起订单时带的订单信息，一般为用户名，交易号，等字段信息
		params.put("robin", "2");//轮训状态，是否开启轮训，状态 1 为关闭   2为开启 
		params.put("amount", price);//支付金额
		params.put("callback_url", CALLBACK_URL);//异步回调地址  请在这里做业务逻辑处理
		params.put("success_url", SUCCESS_URL);//支付成功后返回地址   从支付平台返回商户网站时  不带任何参数
		params.put("error_url", ERROT_URL);//支付失败地址
		
		
		String sign = Demo.getSign(price, "201806261212440");
		
		params.put("sign", sign);// 签名
		params.put("type", "1");//微信支付 1   支付宝 2
		params.put("keyId", "");// 设备KEY 轮询无需填写
		
		/*
			可测试订单是否创建成功  跳转支付接口时 只需要从表单post提交请求就可以了  
		*/
		String order = Demo.post("http://pay.rdnux.cn/gateway/index/checkpoint.do", params);
		// result里含success则创建订单成功  可以对接上自己网站了
		System.out.println("result:" + order);
	}

	/**
	 * sign
	 * 
	 * @param amount
	 *            
	 * @param orderNo
	 *            
	 * @return
	 */
	public static String getSign(String amount, String orderNo) {
		String data = amount + orderNo;

		System.out.println("data:" + data);

		String md5Crypt = MD5Utils.md5(data.getBytes());

		System.out.println("md5Crypt:" + md5Crypt);

		byte[] rc4_string = RC4.encry_RC4_byte(md5Crypt, KEY);

		System.out.println("rc4_string:" + rc4_string);

		String sign = MD5Utils.md5(rc4_string);

		System.out.println("sign:" + sign);
		return sign;
	}

	public static String post(String url, Map<String, String> params) {
		FormBody.Builder builder = new FormBody.Builder();
		for (String key : params.keySet()) {
			builder.add(key, params.get(key).toString());
		}

		RequestBody formBody = builder.build();
		Request request = new Request.Builder().url(url).post(formBody).build();
		String result = null;
		try {
			Response response = client.newCall(request).execute();
			int code = response.code();
			System.err.println("状态码:" + code);
			result = response.body().string();

		} catch (IOException e) {
			e.printStackTrace();
		}
		return result;
	}

	public static String get(String url) {

		Request request = new Request.Builder().url(url).build();
		String json = null;
		okhttp3.Response response = null;
		try {

			response = client.newCall(request).execute();
			json = response.body().string();

		} catch (IOException e) {
			e.printStackTrace();
		}
		return json;
	}
}
