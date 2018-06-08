package util.jiprovince;


import org.apache.commons.lang.StringUtils;
import org.apache.log4j.Logger;

import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.util.Arrays;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;


/**
 * 签名工具类
 * @author Jerry
 * @date 2015.08.13
 */
public class SignatureUtil {
	
	private static Logger logger = Logger.getLogger(SignatureUtil.class);
	
	public static String assmeblyPlainText(Object bean){
		if(null==bean)
			return "";
		Map<String,String> map = new HashMap<String, String>();
		for(Method method:bean.getClass().getMethods()){
			try {
				if(!method.getName().startsWith("get") || "getClass".equalsIgnoreCase(method.getName()))
					continue ; 
				Object o = method.invoke(bean, null);

				/*if((o instanceof Double || o instanceof Integer) && !"-1".equals(o.toString())){
					continue;
				}*/

				if(o!=null&&StringUtils.isNotEmpty(o.toString()) && !"getSignature".equalsIgnoreCase(method.getName().toLowerCase())){
					String feildName = method.getName().substring(3,4).toLowerCase()+method.getName().substring(4);
					if(o instanceof Date){
						map.put(feildName, SdkUtil.formatDate((Date)o, "yyyyMMddHHmmss"));//所有日期类型的统一转换成yyyyMMddHHmmss
					}else {
						map.put(feildName, o.toString());
					}
				}
			} catch (IllegalArgumentException e) {
				e.printStackTrace();
			} catch (IllegalAccessException e) {
				e.printStackTrace();
			} catch (InvocationTargetException e) {
				e.printStackTrace();
			}
		}
		return hex(map);
	}
	
	/**
	 * 排序并组装签名明文串
	 * @param map
	 * @return
	 */
	public static String hex(Map<String,String> map){
		String[] strs = new String[map.size()];
		map.keySet().toArray(strs);
		Arrays.sort(strs);
		StringBuffer source = new StringBuffer();
		for(String str:strs){
			source.append(str+"="+map.get(str)+"&");
		}
		String bigstr = source.substring(0,source.length()-1);
		logger.debug("sign bigstr="+bigstr);
		return bigstr;
	}
	
	/**
	 * 验签
	 * @param signature
	 * @param object
	 * @return
	 */
	public static boolean verify(String signature,Object object,String publicKey){
		String plainText = assmeblyPlainText(object);
		return MyRSAUtils.verifySignature(publicKey, signature, plainText,MyRSAUtils.MD5_SIGN_ALGORITHM);
	}

	//应答签名
	public static String signResp(Map<String, String> respMap) {
		return MyRSAUtils.sign(Constant.PRIVATE_KEY, hex(respMap), MyRSAUtils.MD5_SIGN_ALGORITHM);
	}
	
}
