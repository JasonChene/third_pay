package pass.util;

import java.io.IOException;
import java.security.InvalidKeyException;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.security.SignatureException;
import java.security.spec.InvalidKeySpecException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * 
 * @project entry
 * @description 
 * @author abel.li
 * @creation 2017年3月14日
 * @email 
 * @version
 */
public class Md5 {

    private final static String[] hexDigits = { "0", "1", "2", "3", "4", "5",
        "6", "7", "8", "9", "a", "b", "c", "d", "e", "f" };
    
    /**
     * 通过特定编码格式加密字符串
     * @param origin 需加密的字符串
     * @param charsetName 编码格式
     * @return String 加密后的字符串
     */
    public static String MD5Encode(String origin, String charsetName) {
        origin =origin.trim();
        String resultString = null;
        try {
            resultString = new String(origin);
            MessageDigest md = MessageDigest.getInstance("MD5");
            resultString = byteArrayToHexString(md.digest(resultString.getBytes(charsetName)));
        } catch (Exception ex) {
        }
        return resultString;
    }
    
    public static String byteArrayToHexString(byte[] b) {
        StringBuffer resultSb = new StringBuffer();
        for (int i = 0; i < b.length; i++) {
            resultSb.append(byteToHexString(b[i]));
        }
        return resultSb.toString();
    }
    
    /**
     * Java 转换byte到16进制
     * @param b
     * @return
     */
    private static String byteToHexString(byte b) {
        int n = b;
        if (n < 0) {
            n = 256 + n;
        }
        int d1 = n / 16;
        int d2 = n % 16;
        return hexDigits[d1] + hexDigits[d2];
    }
    
    public static Map<String, String> paraFilter(Map<String, Object> map) {

        Map<String, String> result = new HashMap<>();

        if (map == null || map.size() <= 0) {
            return result;
        }

        for (String key : map.keySet()) {
            String value = map.get(key).toString();
            if (key.equalsIgnoreCase("sign")) {
                continue;
            }
            result.put(key, value);
        }

        return result;
    }

    public static String createLinkString(Map<String, String> map) {

        List<String> keys = new ArrayList<>(map.keySet());
        Collections.sort(keys);

        String prestr = "";

        for (int i = 0; i < keys.size(); i++) {
            String key = keys.get(i);
            String value = map.get(key);

            if (i == keys.size() - 1) {
                //拼接时，不包括最后一个&字符
                prestr = prestr + key + "=" + value;
            } else {
                prestr = prestr + key + "=" + value + "&";
            }
        }

        return prestr;
    }

    public static String getSign(Map<String, Object> map,String key) throws InvalidKeySpecException, SignatureException, NoSuchAlgorithmException, InvalidKeyException, IOException {

        Map<String, String> mapNew = paraFilter(map);

        String preSignStr = createLinkString(mapNew);
//        System.out.println("preSignStr:"+preSignStr);
        String sign = MD5Encode(preSignStr + "&key="+key, "GBK");

        sign = sign.replace("\r\n", "");

        return sign;
    }

    public static Boolean verifySign(Map<String, Object> map,String key) throws Exception {
    	String sign = map.get("sign").toString();
        Map<String, String> mapNew = paraFilter(map);

        String preSignStr = createLinkString(mapNew);
//        System.out.println("preSignStr:"+preSignStr);
        String sign0 = MD5Encode(preSignStr + "&key="+key, "GBK");
//        System.out.println("sign:"+sign);
//        System.out.println("sign0:"+sign0);
        return sign0.equals(sign);
    }
    
}
