package com.sign;

import java.util.Map;
import java.util.TreeMap;

import com.alibaba.fastjson.JSONObject;
import com.sign.config.Config;
import com.sign.http.HttpHelper;
import com.sign.utils.DesHelper;
import com.sign.utils.LogHelpers;
import com.sign.utils.SignHelper;


public class QueryTest {

	public static void main(String[] args) {
		System.out.println("查询接口测试");
	
		String  str=testQuery("test1523945424711", "20180417dc0f2d24a9f6");
			
		System.out.println("查询接口测试平台返回结果"+str);
		
		//校验订单代码-服务器操作
		signResult(str);
	}

	/**
	 * 
	 * @param order_trano_in
	 *            商户订单号
	 * @param order_number
	 *            平台订单号
	 * @return 查询订单结果
	 */
	private static String testQuery(String order_trano_in, String order_number) {
		// 随机字符串
		String nonce = SignHelper.genNonceStr();
		// 时间戳
		long timeStamp = System.currentTimeMillis();
		// 拼凑测试数据
		TreeMap<String, String> map = getOrderResultMap(order_trano_in, order_number);
		// key的字母排序asc
		String data = SignHelper.sortSign(map);
		// 排序后的数据进行MD5加密
		String signature = SignHelper.MD5(timeStamp + nonce + data );
		// 要发送的测试数据转Json格式
		String jsonString = JSONObject.toJSONString(map).toString();
		// 将需要发送的Json数据进行DES加密
		String content = DesHelper.encrypt(jsonString, timeStamp + Config.KEY + nonce).toUpperCase();
		// 打印输出日志
		LogHelpers.Sysos(nonce, timeStamp, data, signature, jsonString, content);
		return HttpHelper.getJsonData(Config.QUERY_URL, Config.KEY, timeStamp + "", nonce, signature, content);
	}


	/**
	 * 组装测试订单数据结果查询
	 * 
	 * @param order_trano_in
	 *            商户订单号
	 * @param order_number
	 *            商户下单成功后获得的平台订单号
	 * @return 测试订单结果查询
	 */
	private static TreeMap<String, String> getOrderResultMap(String order_trano_in, String order_number) {
		TreeMap<String, String> map = new TreeMap<String, String>();
		map.put("order_trano_in", order_trano_in);// 商户订单号
		map.put("order_number", order_number);// 平台订单号
		return map;
	}
	

	/**
	 * 订单验证
	 * @param str 
	 */
	private static void signResult(String str) {
		JSONObject json = JSONObject.parseObject(str);
			JSONObject data = json.getJSONObject("data");
			 
			TreeMap<String, String> map = new TreeMap<String, String>();
			for (Map.Entry<String, Object> entry : data.entrySet()) {
				if (!entry.getKey().equals("signature")) {
					map.put(entry.getKey(), entry.getValue().toString());
				}
			}
			//空值不参与排序
			String signStr = SignHelper.sortSign(map) + Config.KEY;
			System.out.println("排序后拼接Key的字符串:" + signStr);
			String md5 = SignHelper.MD5(signStr);
			if (md5.equals(data.getString("signature"))) {
				System.out.println("md5:" + md5 + ",匹配");
			} else {
				System.out.println("md5:" + md5 + "验证出错");
			}
		}
}
