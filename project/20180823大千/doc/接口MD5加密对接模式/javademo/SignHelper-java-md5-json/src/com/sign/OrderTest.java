package com.sign;


import java.util.TreeMap;

import com.sign.config.Config;
import com.sign.http.HttpHelper;
import com.sign.utils.DesHelper;
import com.sign.utils.LogHelpers;
import com.sign.utils.SignHelper;

import net.sf.json.JSONObject;

public class OrderTest {

	public static void main(String[] args) {
		System.out.println("下单接口测试");
		// 商户订单号
		String order_trano_in = "test" + System.currentTimeMillis();
		// 下单测试
		String orderResult = testOrder(order_trano_in);
		System.out.println("下单接口测试平台返回结果"+orderResult);
	}

	/**
	 * 
	 * @param order_trano_in
	 *            商户订单号
	 * @return 下单交易结果
	 */
	private static String testOrder(String order_trano_in) {
		// 随机字符串
		String nonce = SignHelper.genNonceStr();
		// 时间戳
		long timeStamp = System.currentTimeMillis();
		// 拼凑测试数据
		TreeMap<String, String> map = getOrderContentMap(order_trano_in);
		// key的字母排序asc
		String data = SignHelper.sortSign(map);
		// 排序后的数据进行MD5加密
		String signature = SignHelper.MD5 (timeStamp + nonce + data );
		// 要发送的测试数据转Json格式
		String jsonString = JSONObject.fromObject(map).toString();
		// 将需要发送的Json数据进行DES加密
		String content = DesHelper.encrypt(jsonString, timeStamp + Config.KEY + nonce).toUpperCase();
		//打印输出日志
		LogHelpers.Sysos(nonce, timeStamp, data, signature, jsonString, content);
		return HttpHelper.getJsonData(Config.ORDER_URL, Config.KEY, timeStamp + "", nonce, signature, content);
	}

	
	/**
	 * 测试下单数据组装
	 * 
	 * @param order_trano_in
	 *            商户订单号
	 * @return 测试下单数据
	 */
	private static TreeMap<String, String> getOrderContentMap(String order_trano_in) {
		TreeMap<String, String> map = new TreeMap<String, String>();
		map.put("order_trano_in", order_trano_in);// 商户单号
		map.put("order_goods", "测试商品");// 商品名称
		map.put("order_price", "1111");// 价格 单位分 可空
		map.put("order_num", "100");// 商品数量 可空
		map.put("order_amount", "1342");// 订单金额，单位分 (不能小于100)
		map.put("order_imsi", "1111");// 设备imsi
		map.put("order_extend", "小花篮");// 扩展参数，最大长度64位
		map.put("order_mac", "2222");// 设备mac
		map.put("order_brand", "3333");// 设备品牌
		map.put("order_version", "4444");// 设备系统版本
		map.put("order_ip", "123.123.1.1");// 设备系统版本
		map.put("order_return_url", "https://www.baidu.com");// 成功后同步地址
		map.put("order_notify_url", "https://www.baidu.com");// 异步通知地址
		return map;
	}

}
