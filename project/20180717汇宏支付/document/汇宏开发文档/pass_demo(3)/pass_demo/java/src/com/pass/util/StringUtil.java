package pass.util;

import java.io.IOException;
import java.io.StringReader;
import java.lang.reflect.Field;
import java.security.SecureRandom;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.Random;

import org.jdom.Document;
import org.jdom.Element;
import org.jdom.JDOMException;
import org.jdom.input.SAXBuilder;
import org.xml.sax.InputSource;

import com.alibaba.fastjson.JSONObject;

/**
 * @author hexing E-mail: 383891906@qq.com
 * @version 创建时间：2017年10月06日 下午1:50:56
 */
public class StringUtil extends org.apache.commons.lang3.StringUtils {

	private static SecureRandom random = new SecureRandom();

	public static String fillZero(String str, int size) {
		if (str.length() > size) {
			return str;
		}
		while (str.length() < size) {
			str = "0" + str;
		}
		return str;
	}

	public static List<Integer> tranStringToInteger(String[] strs) {
		List<Integer> list = new ArrayList<Integer>();
		String[] arrayOfString = strs;
		int j = strs.length;
		for (int i = 0; i < j; i++) {
			String str = arrayOfString[i];
			list.add(Integer.valueOf(Integer.parseInt(str)));
		}
		return list;
	}

	public static String upperFirst(String str) {
		return str.substring(0, 1).toUpperCase() + str.substring(1);
	}

	public static boolean isNotBlank(String... str) {
		for (int i = 0; i < str.length; i++) {
			if (isBlank(str[i])) {
				return false;
			}
		}
		return true;
	}

	public static Date parse(String pattern, String source) {
		try {
			return new SimpleDateFormat(pattern).parse(source);
		} catch (ParseException e) {
			e.printStackTrace();
		}
		return null;
	}

	public static Date parse(String source) {
		return parse("yyyy-MM-dd HH:mm:ss", source);
	}

	public static String format(Date date) {
		if (date == null) {
			return "";
		} else {
			return format("yyyy-MM-dd HH:mm:ss", date);
		}
	}

	public static String format(String pattern, Date date) {
		if (date == null) {
			return "";
		} else {
			return new SimpleDateFormat(pattern).format(date);
		}
	}

	/**
	 * @param key
	 *            位数
	 * @return 随机数
	 */
	public static String getRandomNum(int key) {
		Random random = new Random();
		String randomNum = random.nextLong() + "";
		randomNum = randomNum.replaceAll("-", "");
		int randLength = randomNum.length();
		if (randLength < key) {
			for (int i = 1; i <= key - randLength; i++) {
				randomNum = "0" + randomNum;
			}
		} else {
			randomNum = randomNum.substring(0, key);
		}
		return randomNum;
	}

	/**
	 * 翻译性别
	 * 
	 * @param sex
	 * @return
	 */
	public static String tranSex(String sex) {
		if (sex.equals("1")) {
			return "男";
		} else if (sex.equals("0")) {
			return "女";
		} else {
			return "？";
		}
	}

	/**
	 * sql搜索条件拼接
	 * 
	 * @param map
	 * @param key
	 * @param value
	 * @return
	 */
	public static Map<String, String> putMapString(Map<String, String> map, String key, String value) {
		if (StringUtil.isNotBlank(value)) {
			map.put(key, value);
		}
		return map;
	}

	/**
	 * 渠道翻译
	 * 
	 * @param Channel
	 * @return
	 */
	public static String tranChannel(String Channel) {
		if (Channel.equals("alipay")) {
			return "支付宝支付";
		} else if (Channel.equals("wxpay")) {
			return "微信支付";
		} else {
			return "其它支付";
		}
	}

	/**
	 * @description 将xml字符串转换成Map
	 * @param xml
	 * @return json
	 */
	public static Map<String,Object> xmlElements(String xmlDoc) {
		JSONObject json = new JSONObject();
		// 创建一个新的字符串
		StringReader read = new StringReader(xmlDoc);
		// 创建新的输入源SAX 解析器将使用 InputSource 对象来确定如何读取 XML 输入
		InputSource source = new InputSource(read);
		// 创建一个新的SAXBuilder
		SAXBuilder sb = new SAXBuilder();
		try {
			// 通过输入源构造一个Document
			Document doc = sb.build(source);
			// 取的根元素
			Element root = doc.getRootElement();
			// 得到根元素所有子元素的集合
			List<?> jiedian = root.getChildren();
			// 获得XML中的命名空间（XML中未定义可不写）
			Element et = null;
			for (int i = 0; i < jiedian.size(); i++) {
				et = (Element) jiedian.get(i);// 循环依次得到子元素
				json.put(et.getName(), et.getValue() == null ? "" : et.getValue());
			}
		} catch (JDOMException e) {
			// TODO 自动生成 catch 块
			e.printStackTrace();
		} catch (IOException e) {
			// TODO 自动生成 catch 块
			e.printStackTrace();
		}
		return json;
	}


	// 完整的判断中文汉字和符号
	public static boolean isChinese(String strName) {
		char[] ch = strName.toCharArray();
		for (int i = 0; i < ch.length; i++) {
			char c = ch[i];
			if (isChinese(c)) {
				return true;
			}
		}
		return false;
	}

	// 根据Unicode编码完美的判断中文汉字和符号
	private static boolean isChinese(char c) {
		Character.UnicodeBlock ub = Character.UnicodeBlock.of(c);
		if (ub == Character.UnicodeBlock.CJK_UNIFIED_IDEOGRAPHS
				|| ub == Character.UnicodeBlock.CJK_COMPATIBILITY_IDEOGRAPHS
				|| ub == Character.UnicodeBlock.CJK_UNIFIED_IDEOGRAPHS_EXTENSION_A
				|| ub == Character.UnicodeBlock.CJK_UNIFIED_IDEOGRAPHS_EXTENSION_B
				|| ub == Character.UnicodeBlock.CJK_SYMBOLS_AND_PUNCTUATION
				|| ub == Character.UnicodeBlock.HALFWIDTH_AND_FULLWIDTH_FORMS
				|| ub == Character.UnicodeBlock.GENERAL_PUNCTUATION) {
			return true;
		}
		return false;
	}

	public static String traceNo() {

		SimpleDateFormat sdf_no = new SimpleDateFormat("yyyyMMddHHmmssSSS");
		Calendar calendar = Calendar.getInstance();
		String trade_no = sdf_no.format(calendar.getTime()) + (int) (random.nextDouble() * 100000);

		return trade_no;
	}

	/**
	 * 判断对象为空
	 * 
	 * @param obj
	 *            对象名
	 * @return 是否为空
	 */
	@SuppressWarnings("rawtypes")
	public static boolean isEmpty(Object obj) {
		if (obj == null) {
			return true;
		}
		if ((obj instanceof List)) {
			return ((List) obj).size() == 0;
		}
		if ((obj instanceof String)) {
			return ((String) obj).trim().equals("");
		}
		return false;
	}

	/**
	 * 判断对象不为空
	 * 
	 * @param obj
	 *            对象名
	 * @return 是否不为空
	 */
	public static boolean isNotEmpty(Object obj) {
		return !isEmpty(obj);
	}

	/***
	 * convert map to object ,see setObjectValue(obj, map)
	 * 
	 * @param map
	 *            : key是对象的成员变量,其value就是成员变量的值
	 * @param clazz
	 * @return
	 * @throws InstantiationException
	 * @throws IllegalAccessException
	 * @throws SecurityException
	 * @throws NoSuchFieldException
	 * @throws IllegalArgumentException
	 */
	@SuppressWarnings("rawtypes")
	public static Object convertMap2Obj(Map<String, Object> map, Class clazz) throws InstantiationException,
			IllegalAccessException, SecurityException, NoSuchFieldException, IllegalArgumentException {
		if (isEmpty(map)) {
			return null;
		}
		Object obj = clazz.newInstance();
		setObjectValue(obj, map);
		/*
		 * for(Iterator it=map.entrySet().iterator();it.hasNext();){ Map.Entry<String,
		 * Object> entry=(Map.Entry<String, Object>)it.next(); String
		 * key=entry.getKey(); Object val=entry.getValue(); }
		 */
		return obj;
	}

	/***
	 * 利用反射设置对象的属性值. 注意:属性可以没有setter 方法.
	 * 
	 * @param obj
	 * @param params
	 * @throws SecurityException
	 * @throws NoSuchFieldException
	 * @throws IllegalArgumentException
	 * @throws IllegalAccessException
	 */
	public static void setObjectValue(Object obj, Map<String, Object> params)
			throws SecurityException, IllegalArgumentException, IllegalAccessException, NoSuchFieldException {
		if (isEmpty(obj)) {
			return;
		}
		if (isEmpty(params)) {
			return;
		}
		Class<?> clazz = obj.getClass();
		for (Iterator<Entry<String, Object>> it = params.entrySet().iterator(); it.hasNext();) {
			Map.Entry<String, Object> entry = (Map.Entry<String, Object>) it.next();
			String key = entry.getKey();
			Object propertyValue = entry.getValue();
			if (isEmpty(propertyValue)) {
				continue;
			}
			Field name = clazz.getDeclaredField(key);
			if (name != null) {
				name.setAccessible(true);
				name.set(obj, propertyValue);
			}
		}

	}

	public static Map<String,Object> covertObj2Map(Object object){
		Map<String,Object> map = new HashMap<String,Object>();
		Object value = null;
    	Field[] fs = object.getClass().getDeclaredFields();
        for (Field f:fs){
            if (f.getName().equals("serialVersionUID")){
                continue;
            }
            f.setAccessible(true);
			try {
				value = f.get(object);
			} catch (IllegalArgumentException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (IllegalAccessException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
            if(value==null){
            	value="";
            }
            String name = f.getName();
            map.put(name, value);
        }
        return map;
	}
	
}
