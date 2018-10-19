package com.pay.demo;

/**
 * 支付回调的参数实体类
 * 
 */
public class PayEntity {

	/**
	 * 您的自定义订单号
	 */
	private String order_number;

	/**
	 * 秘钥
	 */
	private String key;

	/**
	 * 实际支付金额
	 */
	private String amount;

	public String getOrder_number() {
		return order_number;
	}

	public void setOrder_number(String order_number) {
		this.order_number = order_number;
	}

	public String getKey() {
		return key;
	}

	public void setKey(String key) {
		this.key = key;
	}

	public String getAmount() {
		return amount;
	}

	public void setAmount(String amount) {
		this.amount = amount;
	}

}
