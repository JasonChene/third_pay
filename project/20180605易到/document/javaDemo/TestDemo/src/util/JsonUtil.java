package util;

import java.lang.reflect.Array;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import net.sf.json.JSONArray;
import net.sf.json.JSONNull;
import net.sf.json.JSONObject;

public class JsonUtil {
	
	public static Map jsonToMap(String json) { 		
		Map classMap = new HashMap();
		classMap.put("map", Map.class);
		Map map = (Map)JSONObject.toBean(JSONObject.fromObject(json), Map.class, classMap);
		// 转换null
		Iterator it=map.keySet().iterator();
		while(it.hasNext()){
			String key = (String)it.next();
			Object value = map.get(key);
			if (value instanceof JSONNull) {
				map.put(key, null);
			}
		}
		return map;
	}
	
	public static String map2Json(Map map) {
		JSONObject j = JSONObject.fromObject(map);
		return j.toString();
	}
	
	public static Map jsonArrayToMap(String json) { 		
		Map classMap = new HashMap();
		classMap.put("map", Map.class);
		Map map = (Map)JSONObject.toBean(JSONObject.fromObject(json), Map.class, classMap);
		// 转换null
		Iterator it=map.keySet().iterator();
		while(it.hasNext()){
			String key = (String)it.next();
			Object value = map.get(key);
			if (value instanceof JSONNull) {
				map.put(key, null);
			}
			if (value instanceof ArrayList) {
				List list = (ArrayList)value;
				StringBuffer sb = new StringBuffer();
				for(int i = 0 ;i<list.size();i++){
					if(i==0){
						sb.append(list.get(i));
					}else{
						sb.append(";"+list.get(i));
					}
				}
				map.put(key,sb.toString() );
			}
		}
		return map;
	}
	
	
	public static void main(String[] args) {
		String s ="{'dateTime':'2014-03-26 17:20:41','maskedPAN':'333333******5456','torderId':'8119E99A33656CFBD94BBD72F4A3B0EF','transType':'消费','retCode':'0000','issuingBank':'','terSerialNo':'0000000000000202','funCode':'7001','retMsg':'成功','seq':'20140529110841000016','merchantName':'小罗','merchantNo':'801401081714097','referenceNo':null,'orderAmt':'22.22'}";
		Map map = jsonToMap(s);
		String aa = (String)map.get("referenceNo");
		System.out.println(aa);
	
	}
}
