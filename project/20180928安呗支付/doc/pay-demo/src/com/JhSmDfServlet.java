﻿package com;


import org.codehaus.xfire.client.Client;

import java.net.URL;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

/**
 * 扫码    ，
 * 
 * @author Administrator
 * 
 */
public class JhSmDfServlet {

	public static void main(String[] args) {
        try {
//            String serviceUrl = "http://114.215.172.196:7077/pay/CXFServlet/PaySmService?wsdl";
            String serviceUrl = "http://localhost:8087/pay/CXFServlet/PaySmService?wsdl";
            Map<String,String> map=new HashMap<String, String>();
            map.put("mid","105471000031");
            map.put("orderNo", "TestBYT" + new SimpleDateFormat("yyyyMMddHHmmss").format(new Date()));
            map.put("amount","1.00");
            map.put("receiveName", "xxxx");
            map.put("openProvince", "xx");
            map.put("openCity","xx");
            map.put("bankCode","310");
	    map.put("bankname"," xxxx");
            map.put("bankBranchName","xxxx");
            map.put("cardNo","6xxxxxxx5");
            map.put("mobile","1xxxxx0");
            map.put("bankLinked","308393033110");
            map.put("type","union_sm");//支付种类:union_sm:银联扫码;QQwap:QQ钱包Wap;QQwallet:QQ钱包扫码
            map.put("cardAccountType","01");
		List<Map.Entry<String, String>> infoIds = new ArrayList<Map.Entry<String, String>>(map.entrySet());
            // 对所有传入参数按照字段名的 ASCII 码从小到大排序（字典序）
            Collections.sort(infoIds, new Comparator<Map.Entry<String, String>>() {

                public int compare(Map.Entry<String, String> o1, Map.Entry<String, String> o2) {
                    return (o1.getKey()).toString().compareTo(o2.getKey());
                }
            });

            // 构造签名键值对的格式
            StringBuilder sb = new StringBuilder();
            for (Map.Entry<String, String> item : infoIds) {
                if (item.getKey() != null && item.getKey() != "") {
                    String key = item.getKey();
                    String val = item.getValue().toString().replace("\"", "");
                    if(val!=null&&val!=""){
                        sb.append(key + "=" + val + "&");
                    }
                }
            }

            String posdata=sb.toString();
            posdata = posdata.substring(0, posdata.length() - 1);
            posdata = posdata + "&key" + "=" + "xxxxxxx";
            System.out.println("posdata:" + posdata);
            map.put("sign",DesUtil.encrypt("10547100xxxx",posdata));
		
            String val= JsonUtil.mapToJson(map);
            System.out.println(val);
            Client client = new Client(new URL(serviceUrl));
            Object[] results = client.invoke("trans", new Object[] {val });
            System.out.println(results[0]);
        } catch (Exception e) {
            e.printStackTrace();
        }

	}
}
