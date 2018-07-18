package pass.demo;

import java.net.URLDecoder;
import java.net.URLEncoder;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.UUID;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import pass.util.HttpUtil;
import pass.util.Md5;
import pass.util.StringUtil;

public class HttpTest {

	static HttpUtil http = new HttpUtil();
	
	@SuppressWarnings("static-access")
	private static void orderCreate() throws Exception {
		
		int machineId = 1;// 最大支持1-9个集群机器部署
		int hashCodeV = UUID.randomUUID().toString().hashCode();
		if (hashCodeV < 0) {// 有可能是负数
			hashCodeV = -hashCodeV;
		}
		// 0 代表前面补充0;d 代表参数为正数型
		String orderid = machineId + String.format("%01d", hashCodeV);
		
		Map<String,Object> map = new HashMap<String,Object>();
		map.put("orderid", orderid);//商户号
		map.put("istype", "1");//支付方式
		map.put("price", "10");//订单标题
		map.put("notify_url", "http://123.207.108.152/notify2");//订单详情
		map.put("mchno", "M201801010001");//商户号
    	
    	String sign = Md5.getSign(map, "12345678901234567890123456789012");//签名
    	
    	map.put("sign", sign);
    	
    	Document doc = DocumentHelper.createDocument();
		Element root = doc.addElement("xml");

		Iterator<String> it = map.keySet().iterator();
		while (it.hasNext()) {
			String key = it.next().toString();
			String value = map.get(key).toString();

			root.addElement(key).addText(value);
		}
		String reqBody = "<?xml version=\"1.0\" encoding=\"GBK\" standalone=\"yes\"?>"
				+ doc.getRootElement().asXML();
    	
    	String data = URLEncoder.encode(reqBody, "GBK");
    	
    	String result = http.sendPost("http://123.207.108.152/preCreate", "req="+data);
    	result = URLDecoder.decode(result, "GBK");
    	
    	Map<String,Object> result_map = StringUtil.xmlElements(result);
    	
    	if(Md5.verifySign(result_map, "12345678901234567890123456789012")) {
    		System.out.println(result);
    	}else {
    		System.out.println(false);
    	}
    	
	}
	
	@SuppressWarnings("static-access")
	private static void selectOrder() throws Exception {
		Map<String,Object> map = new HashMap<String,Object>();
		map.put("orderid", "1815587119");//订单号
		map.put("mchno", "M201801010001");//商户号
    	
		String sign = Md5.getSign(map, "12345678901234567890123456789012");
		
		map.put("sign", sign);
		
		Document doc = DocumentHelper.createDocument();
		Element root = doc.addElement("xml");

		Iterator<String> it = map.keySet().iterator();
		while (it.hasNext()) {
			String key = it.next().toString();
			String value = map.get(key).toString();

			root.addElement(key).addText(value);
		}
		String reqBody = "<?xml version=\"1.0\" encoding=\"GBK\" standalone=\"yes\"?>"
				+ doc.getRootElement().asXML();
    	
    	String data = URLEncoder.encode(reqBody, "GBK");
    	
    	String result = http.sendPost("http://123.207.108.152/selectOrder", "req="+data);
    	result = URLDecoder.decode(result, "GBK");
    	Map<String,Object> result_map = StringUtil.xmlElements(result);
    	
    	if(Md5.verifySign(result_map, "12345678901234567890123456789012")) {
    		System.out.println(result);
    	}else {
    		System.out.println(false);
    	}
	}
	
    public static void main(String[] args) throws Exception {
    	orderCreate();
//    	selectOrder();
    	
    }
}
