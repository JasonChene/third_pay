package com.pay.demo;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;
import java.util.UUID;

public class PayUtil {

	//UID
	public static String UID = "uid";
	//支付成功后我们回调给你的回调地址
	public static String NOTIFY_URL = "http://pays.com/notifyPay";
	//支付成功或失败自动跳转的地址
	public static String RETURN_URL = "http://pays.com";
	//支付接口地址
	public static String BASE_URL = "http://pay.pays.com";
	//密钥
	public static String MERKEY = "密钥";
	
	/**
	 * 构造密钥 主方法
	 * @param remoteMap
	 * @return
	 */
	public static Map<String, Object> payOrder(Map<String, Object> remoteMap) {
		Map<String, Object> paramMap = new HashMap<String, Object>();
		paramMap.put("uid", UID);
		paramMap.put("base_url", BASE_URL);
		paramMap.put("notify_url", remoteMap.get("notify_url") == null ? NOTIFY_URL : remoteMap.get("notify_url"));
		paramMap.put("return_url", remoteMap.get("return_url") == null ? RETURN_URL : remoteMap.get("return_url"));
		paramMap.putAll(remoteMap);
		paramMap.put("key", getKey(paramMap));
		return paramMap;
	}

	/**
	 * 拼接字符串
	 * @param remoteMap
	 * @return
	 */
	public static String getKey(Map<String, Object> remoteMap) {
		String key = "";
		if (null != remoteMap.get("notify_url")) {
			key += remoteMap.get("notify_url");
		}
		if (null != remoteMap.get("order_number")) {
			key += remoteMap.get("order_number");
		}
		if (null != remoteMap.get("order_uid")) {
			key += remoteMap.get("order_uid");
		}
		if (null != remoteMap.get("qr_amount")) {
			key += remoteMap.get("qr_amount");
		}
		if (null != remoteMap.get("return_url")) {
			key += remoteMap.get("return_url");
		}
		if (null != remoteMap.get("type")) {
			key += remoteMap.get("type");
		}
		if (null != remoteMap.get("uid")) {
			key += remoteMap.get("uid");
		}
		key += MERKEY;
		return MD5Kit.MD5_32(key);
	}

	/**
	 * 回调参数验证
	 * @param payShelp
	 * @return
	 */
	public static boolean checkPayKey(PayEntity payShelp) {
		String key = "";
		if (!isBlank(payShelp.getAmount())) {
			System.out.println("支付回来的金額：" + payShelp.getAmount());
			key += payShelp.getAmount();
		}
		if (!isBlank(payShelp.getOrder_number())) {
			System.out.println("支付回来的订单号：" + payShelp.getOrder_number());
			key += payShelp.getOrder_number();
		}
		System.out.println("支付回来的Key：" + payShelp.getKey());
		key += MERKEY;
		System.out.println("我们自己拼接的Key：" + MD5Kit.MD5_32(key));
		return payShelp.getKey().equals(MD5Kit.MD5_32(key));
	}

	/**
	 * 验证是否为空 true为空
	 * @param str
	 * @return
	 */
	public static boolean isBlank(String str){
		if(null != str || !"".equals(str)) {
			return false;
		}
		return true;
	}
	
	/**
	 * 生成自定义订单号
	 * @return
	 */
	public static String getOrderIdByUUId() {
		int machineId = 1;// 最大支持1-9个集群机器部署
		int hashCodeV = UUID.randomUUID().toString().hashCode();
		if (hashCodeV < 0) {// 有可能是负数
			hashCodeV = -hashCodeV;
		}
		// 0 代表前面补充0;d 代表参数为正数型
		SimpleDateFormat dateFormat = new SimpleDateFormat("yyyyMMddHHmmss");
		return dateFormat.format(new Date()) + machineId
				+ String.format("%01d", hashCodeV);
	}

}
