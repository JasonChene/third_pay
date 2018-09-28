package com;


import org.codehaus.xfire.client.Client;

import java.net.URL;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

/**
 * 快捷后台交易，
 * 
 * @author Administrator
 * 
 */
public class JhKjhtServlet {

    public static void main(String[] args) {
        pay();
//        sendCode();
    }


	//发送短信验证码
	public static void sendCode() {
        try {
//            String serviceUrl = "http://114.215.172.196:7077/pay/CXFServlet/PaySmService?wsdl";
            String serviceUrl = "http://localhost:8087/pay/CXFServlet/PaySmService?wsdl";
            Map<String,String> map=new HashMap<String, String>();
            map.put("mid","105471000031");
            map.put("subject","快捷-测试");
            map.put("orderNo", "TestBYT" + new SimpleDateFormat("yyyyMMddHHmmss").format(new Date()));
            map.put("amount","12.00");
            map.put("acctNo", "622XXXXXXXXXXX");
            map.put("accType", "01");//交易类型01：储蓄卡 02：信用卡
            map.put("cvn2", "");
            map.put("expDate","");
            map.put("bankName","建设银行");
            map.put("phone","18XXXXXXXX
            map.put("userName","吴舒锴");
            map.put("certNo","350XXXXXXXXXXXXX");
            map.put("notifyUrl","http://114.215.172.196:7077/pay/payAsyn.action?act=textAsny");
            map.put("sign", DesUtil.encrypt("105471000031", "e88f9384-a6d6-4c63-97b8-8212149f994d"));


            String val= JsonUtil.mapToJson(map);
            System.out.println(val);
//            Client client = new Client(new URL(serviceUrl));
//            Object[] results = client.invoke("QuickSms", new Object[] {val });
//            System.out.println(results[0]);
        } catch (Exception e) {
            e.printStackTrace();
        }

	}

	//确认支付
    public static void pay() {
        try {
//            String serviceUrl = "http://114.215.172.196:7077/pay/CXFServlet/PaySmService?wsdl";
            String serviceUrl = "http://localhost:8087/pay/CXFServlet/PaySmService?wsdl";
            Map<String,String> map=new HashMap<String, String>();
            map.put("mid","105471000031");
            map.put("subject","快捷-测试");
            map.put("orderNo", "TestBYT" + new SimpleDateFormat("yyyyMMddHHmmss").format(new Date()));
            map.put("amount","12.00");
            map.put("acctNo", "622XXXXXXXXXXX");
            map.put("accType", "01");//交易类型01：储蓄卡 02：信用卡
            map.put("cvn2", "");
            map.put("expDate","");
            map.put("bankName","建设银行");
            map.put("phone","18XXXXXXXX
            map.put("userName","吴舒锴");
            map.put("certNo","350XXXXXXXXXXXXX");
            map.put("notifyUrl","http://114.215.172.196:7077/pay/payAsyn.action?act=textAsny");
            map.put("noCardNum", "083642");
            map.put("oem_systrace","68748fc841cb46c4bc014e23658e37dc");
            map.put("sign", DesUtil.encrypt("105471000031", "e88f9384-a6d6-4c63-97b8-8212149f994d"));


            String val= JsonUtil.mapToJson(map);
            System.out.println(val);
            Client client = new Client(new URL(serviceUrl));
            Object[] results = client.invoke("QuickPay", new Object[] {val });
            System.out.println(results[0]);
        } catch (Exception e) {
            e.printStackTrace();
        }

    }
}
