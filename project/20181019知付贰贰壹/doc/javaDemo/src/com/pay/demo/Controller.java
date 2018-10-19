package com.pay.demo;

import java.util.HashMap;
import java.util.Map;

/**
 * 主测试类
 * 
 * @author pay
 *
 */
public class Controller {
	public static void main(String[] args) {
		//1.获取支付参数 将参数输出到页面上 from表单提交过来
		Map<String, Object> data = getPayParam("50", 1, "1111");
		for (Map.Entry<String, Object> entry : data.entrySet()) {
			System.out.println(entry.getKey() + ":" + entry.getValue());
		}
		
		//2.验证回调 很重要，防止非法回调
		String amount = "50";
		String order_number = "";
		String key = "";
		PayEntity payEntity = new PayEntity();
		payEntity.setKey(key);
		payEntity.setAmount(amount);
		payEntity.setOrder_number(order_number);
		if (PayUtil.checkPayKey(payEntity)) {
			System.out.println("验证通过，执行充值流程....");
		}
	}

	/**
	 * 获取支付参数 将自己的参数替换
	 * 
	 * @param amount 支付金额
	 * @param type 支付类型 1支付宝 2微信
	 * @param order_uid 发起支付的用户ID（可自定义参数）
	 * @return
	 */
	public static Map<String, Object> getPayParam(String amount, Integer type,String order_uid) {
		Map<String, Object> remoteMap = new HashMap<String, Object>();
		remoteMap.put("qr_amount", amount);
		remoteMap.put("order_number", PayUtil.getOrderIdByUUId());
		remoteMap.put("order_uid", order_uid);
		remoteMap.put("type", type);
		return PayUtil.payOrder(remoteMap);
	}
}