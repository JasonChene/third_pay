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
	private static final String CALLBACK_URL = "http://www.baidu.com/";
	private static final String SUCCESS_URL = "http://www.baidu.com/";
	private static final String ERROT_URL = "http://www.baidu.com/";
	private static final String KEY = "4C61C86ABEBC7249";
	private static OkHttpClient client = new OkHttpClient();

	public static void main(String[] args) {
		HashMap<String, String> params = new HashMap<>();
		params.put("account_id", "10000");// 商户ID
		params.put("content_type", "json");// 网页类型
		params.put("thoroughfare", "service_auto");// 支付通道
		params.put("out_trade_no", "201806261212440");// 订单信息
		params.put("robin", "2");// 轮训状态 //2开启1关闭
		params.put("amount", "20.01");// 支付金额
		params.put("callback_url", CALLBACK_URL);// 异步通知url
		params.put("success_url", SUCCESS_URL);// 支付成功后跳转到url
		params.put("error_url", ERROT_URL);// 支付失败后跳转到url
		String sign = Demo.getSign("20.01", "201806261212440");
		params.put("sign", sign);// 签名算法
		params.put("type", "1");// 支付类型 //1为微信，2为支付宝
		params.put("keyId", "");// 设备KEY 轮询无需填写

		String order = Demo.post("https://payme.cn.com", params);
		// 获取结果
		System.out.println("result:" + order);

		JSONObject object = JSONObject.parseObject(order);
		JSONObject object2 = object.getJSONObject("data");
		String order_id = object2.getString("order_id");

		String result = Demo.get("https://payme.cn.com/gateway/pay/service.do?content_type=json&id=" + order_id);
		System.out.println("result:" + result);
	}

	/**
	 * 
	 * @param account_name
	 *            商户名称
	 * @param pay_time
	 *            支付时间戳
	 * @param status
	 *            支付状态
	 * @param amount
	 *            支付金额
	 * @param out_trade_no
	 *            订单信息
	 * @param trade_no
	 *            交易流水号
	 * @param fees
	 *            该订单手续费
	 * @param sign
	 *            订单签名
	 * @param callback_time
	 *            回调时间
	 * @param type
	 *            支付类型
	 * @param account_key
	 *            商户KEY（S_KEY）
	 * @return
	 */
	public static String notify(String account_name, //
			String pay_time, //
			String status, //
			String amount, //
			String out_trade_no, //
			String trade_no, //
			String fees, //
			String sign, //
			String callback_time, //
			String type, //
			String account_key) {
		
		//验证key是否正确
		if (!KEY.equalsIgnoreCase(account_key)) {
			return "error";
		}
		//验证签名是否正确
		String s = Demo.getSign(amount, out_trade_no);
		if (!s.equalsIgnoreCase(sign)) {
			return "sign error";
		}
		return "success";
	}

	/**
	 * 获得sign
	 * 
	 * @param amount
	 *            金额
	 * @param orderNo
	 *            订单信息
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
