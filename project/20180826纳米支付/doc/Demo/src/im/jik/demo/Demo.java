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
	private static final String KEY = "3b823896B8E09b";    //商户Key
	private static OkHttpClient client = new OkHttpClient();

	public static void main(String[] args) {
		String price = "2.01";
		String out_trade_no = "1111";
		HashMap<String, String> params = new HashMap<>();
		params.put("account_id", "10009");// �商户Id，再平台首页右边获取商户Id  此处为10008不可更改
		params.put("content_type", "text");// 请求过程中返回的网页类型，text或json   建议用jso
		params.put("thoroughfare", "service_auto");// 不可更改
		params.put("out_trade_no",out_trade_no );// 可用用户或者订单Id
		params.put("robin", "2");// 轮训，2：开启轮训，1：进入单通道模式    这边使用2不可更改
		params.put("amount", price);// 	支付金额，在发起时用户填写的支付金额
		params.put("callback_url", CALLBACK_URL);// 	异步通知地址，在支付完成时，本平台服务器系统会自动向该地址发起一条支付成功的回调请求
		params.put("success_url", SUCCESS_URL);// 支付成功后网页自动跳转地址，仅在网页类型为text下有效，json会将该参数返回
		params.put("error_url", ERROT_URL);// 支付失败时，或支付超时后网页自动跳转地址，仅在网页类型为text下有效，json会将该参数返回
		String sign = Demo.getSign(price, out_trade_no);
		params.put("sign", sign);// 签名
		params.put("type", "2");//微信：1，支付宝：2    用户可选

		String order = Demo.post("http://ej08.com/gateway/index/checkpoint.do", params);
		// ��ȡ���
		System.out.println("result:" + order);

		JSONObject object = JSONObject.parseObject(order);
		JSONObject object2 = object.getJSONObject("data");
		String order_id = object2.getString("order_id");
		String result = Demo.get("http://ej08.com/gateway/index/service.do?content_type=json&id="+order_id);
		System.out.println("result:" + result);
	}

	/**
	 * ���sign
	 * 
	 * @param amount
	 *            ���
	 * @param orderNo
	 *            ������Ϣ
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
			System.err.println("״̬��:" + code);
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
