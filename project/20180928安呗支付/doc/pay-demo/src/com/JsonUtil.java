package com;

import java.io.PrintWriter;
import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.Set;


import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

import javax.servlet.http.HttpServletResponse;

public class JsonUtil {

	public static Gson create(){
		GsonBuilder gsonBuilder = new GsonBuilder();  
		gsonBuilder.setDateFormat("yyyy/MM/dd HH:mm:ss");
		return gsonBuilder.create();  
	}
	
	//对象转json
	public static String toJson(Object object) {
		try {
			String str = JsonUtil.create().toJson(object);
			return str;
		} catch (Exception e) {
			return "";
		}
	}
	
	//往页面写数据
	public static void outWrite(HttpServletResponse response,String data){
		PrintWriter out = null;
		try {
			out = response.getWriter();
		} catch (Exception e) {
		}finally {
			if(out!=null){
				if(data!=null){
					out.write(data);
				}
				out.close();
			}
		}
	}
	
	//对象转json加[]
	public static String toJsonforTree(Object object) {
		try {
			String str = JsonUtil.create().toJson(object);
			if(str!=null&&str.length()>0&&!str.substring(0, 1).equals("[")){
				str = "["+str+"]";
			}
			return str;
		} catch (Exception e) {
			return "";
		}
	}
	

	
	
	  /** 
     * json字符串转成对象 
     * @param str   
     * @param type 
     * @return  
     */  
    public static <T> T fromJson(String str, Type type) {  
        Gson gson = new Gson();  
        return gson.fromJson(str, type);  
    }  
  
    /** 
     * json字符串转成对象 
     * @param str   
     * @param type  
     * @return  
     */  
    public static <T> T fromJson(String str, Class<T> type) {  
        Gson gson = new Gson();  
        return gson.fromJson(str, type);  
    }

	public static Map<String,Object> toMap(String json){
		return JsonUtil.toMap(JsonUtil.parseJson(json));
	}


	/**
	 * 获取JsonObject
	 * @param json
	 * @return
	 */
	public static JsonObject parseJson(String json){
		JsonParser parser = new JsonParser();
		JsonObject jsonObj = parser.parse(json).getAsJsonObject();
		return jsonObj;
	}

	/**
	 * 将JSONObjec对象转换成Map-List集合
	 * @param json
	 * @return
	 */
	public static Map<String, Object> toMap(JsonObject json){
		Map<String, Object> map = new HashMap<String, Object>();
		Set<Map.Entry<String, JsonElement>> entrySet = json.entrySet();
		for (Iterator<Entry<String, JsonElement>> iter = entrySet.iterator(); iter.hasNext(); ){
			Map.Entry<String, JsonElement> entry = iter.next();
			String key = entry.getKey();
			Object value = entry.getValue();
			if(value instanceof JsonArray)
				map.put((String) key, toList((JsonArray) value));
			else if(value instanceof JsonObject)
				map.put((String) key, toMap((JsonObject) value));
			else
				map.put((String) key, value);
		}
		return map;
	}

	/**
	 * 将JSONArray对象转换成List集合
	 * @param json
	 * @return
	 */
	public static List<Object> toList(JsonArray json){
		List<Object> list = new ArrayList<Object>();
		for (int i=0; i<json.size(); i++){
			Object value = json.get(i);
			if(value instanceof JsonArray){
				list.add(toList((JsonArray) value));
			}
			else if(value instanceof JsonObject){
				list.add(toMap((JsonObject) value));
			}
			else{
				list.add(value);
			}
		}
		return list;
	}

	public static <T> String mapToJson(Map<String, T> map) {
		Gson gson = new Gson();
		String jsonStr = gson.toJson(map);
		return jsonStr;
	}
}
