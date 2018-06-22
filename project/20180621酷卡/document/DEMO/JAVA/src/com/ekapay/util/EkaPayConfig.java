package com.ekapay.util;

import java.util.ResourceBundle;

public class EkaPayConfig {

	private static Object lock              = new Object();
	private static EkaPayConfig config     = null;
	private static ResourceBundle rb        = null;
	private static final String CONFIG_FILE = "com.ekapay.common.ParterInfo";
	
	private EkaPayConfig() {
		rb = ResourceBundle.getBundle(CONFIG_FILE);
	}
	
	public static EkaPayConfig getInstance() {
		synchronized(lock) {
			if(null == config) {
				config = new EkaPayConfig();
			}
		}
		return (config);
	}
	
	public String getValue(String key) {
		return (rb.getString(key));
	}
}
