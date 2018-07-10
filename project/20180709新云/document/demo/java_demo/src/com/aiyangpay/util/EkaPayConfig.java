package com.aiyangpay.util;

import java.util.ResourceBundle;

public class aiyangpayConfig {

	private static Object lock              = new Object();
	private static aiyangpayConfig config     = null;
	private static ResourceBundle rb        = null;
	private static final String CONFIG_FILE = "com.leshouka.common.ParterInfo";
	
	private aiyangpayConfig() {
		rb = ResourceBundle.getBundle(CONFIG_FILE);
	}
	
	public static aiyangpayConfig getInstance() {
		synchronized(lock) {
			if(null == config) {
				config = new aiyangpayConfig();
			}
		}
		return (config);
	}
	
	public String getValue(String key) {
		return (rb.getString(key));
	}
}
