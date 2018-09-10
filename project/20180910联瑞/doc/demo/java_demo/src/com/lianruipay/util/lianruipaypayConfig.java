package com.lianruipaypay.util;

import java.util.ResourceBundle;

public class lianruipaypayConfig {

	private static Object lock              = new Object();
	private static lianruipaypayConfig config     = null;
	private static ResourceBundle rb        = null;
	private static final String CONFIG_FILE = "com.leshouka.common.ParterInfo";
	
	private lianruipaypayConfig() {
		rb = ResourceBundle.getBundle(CONFIG_FILE);
	}
	
	public static lianruipaypayConfig getInstance() {
		synchronized(lock) {
			if(null == config) {
				config = new lianruipaypayConfig();
			}
		}
		return (config);
	}
	
	public String getValue(String key) {
		return (rb.getString(key));
	}
}
