package com;


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
public class JhSmServlet {

	public static void main(String[] args) {
        try {
            String serviceUrl = "http://114.215.172.196:7077/pay/CXFServlet/PaySmService?wsdl";
//            String serviceUrl = "http://localhost:8087/pay/CXFServlet/PaySmService?wsdl";
            Map<String,String> map=new HashMap<String, String>();
            map.put("mid","105471000031");//商户号
            map.put("orderNo", "TestBYT" + new SimpleDateFormat("yyyyMMddHHmmss").format(new Date()));//商户订单号
            map.put("subject", "TEST");//订单标题
            map.put("body", "TEST");//订单描述
            map.put("amount","20.00");//订单金额
            map.put("type","QQwallet");//支付种类:union_sm:银联扫码;QQwap:QQ钱包Wap;QQwallet:QQ钱包扫码
            map.put("notifyUrl","http://114.215.172.196:7077/pay/payAsyn.action?act=textAsny");//后台通知   URL
            map.put("buyerName","测试人");//买家姓名
            map.put("buyerId","e88f9384-a6d6-4c63-97b8-8212149f994d");//买家在商城的唯一编号
            map.put("payRemark","测试付款");//付款摘要
            map.put("extNetIp","114.215.172.196");//用户设备外网
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
//            Client client = new Client(new URL(serviceUrl));
//            Object[] results = client.invoke("pay", new Object[] {val });
//            System.out.println(results[0]);
        } catch (Exception e) {
            e.printStackTrace();
        }

	}
}
