package test.tianye;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.text.SimpleDateFormat;
import java.util.Arrays;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

import org.apache.log4j.Logger;

import com.hispeed.channel.service.PayService;
import com.hispeed.dao.AppDAO;
import com.hispeed.entity.GwAcsAppInfoEntity;
import com.hispeed.util.StringUtil;

public class Test_Notice {
	private static Logger logger = Logger.getLogger("logger");
	public static void main(String[] args){
		PayService service = new PayService();
		service.sendNotify("TNO_01201801120945522961003", "0", "1", "mx_guofu", "7236488596234199040", "success", "http://pay.hcoriental.com/api/v1/pay/guofupay/notify/mx_guofu");
	}
	
	public String sendNotify(String tradesno,String status,String realamount,String appid,String apporderid,String statusdesc,String notifyurl){
		SimpleDateFormat d = new SimpleDateFormat("yyyyMMddHHmmss");
		String result = null;
		Map<String, String> map = new HashMap<String, String>();
		map.put("tradesno", tradesno);//商户id
		map.put("status", status);
		map.put("realamount",realamount);
		map.put("appid",appid);
		map.put("apporderid",apporderid);
		map.put("statusdesc",statusdesc);
		map.put("timeend",d.format(new Date()));
		AppDAO dao = new AppDAO();
		GwAcsAppInfoEntity entity = dao.getAppInfoById(appid);
		//result = this.invoke(map, notifyurl, "7xk2J6i6pEvwbOtH38gJzBWCDdI1ob3RIWNwR4Ll6u3m8c1DIoNMXEadiTUqQ9spncnUO9YJFPdsOSeuqJQ2bo5zvf6dgVxA8dQx1k17CmwZHl7b1pb7MlPEmsAknXia");
		result = this.invoke(map, notifyurl, entity.getAppKey());
		return result;
	}
	
	public String invoke(Map<String, String> paramMap, String url, String appInitKey) {
		Map<String, String> resultMap = new HashMap<String, String>();
		String params = createParam(paramMap, appInitKey);
		logger.info(params);
		logger.info(url);
		String result = null;
		try{
			result = submitPost(url, params);
			if (StringUtil.isEmpty(result)) {
				logger.info("通知失败");
				return "N";
			}
			logger.info("商户返回："+result);
			logger.info("---------");
			String[] split = result.split("&");
			for (int i = 0; i < split.length; i++) {
				String[] temp = split[i].split("=");
				if (temp.length == 1) {
					resultMap.put(temp[0], "");
				}
				if (temp.length > 1) {
					resultMap.put(temp[0], temp[1]);
				}
			}
			return result;
		}catch(Exception e){
			logger.info(e.getMessage());
			return "N";
		}

		
	}
	
	public String createParam(Map<String, String> map, String appInitKey) {
		try {
			if (map == null || map.isEmpty()) {
				return null;
			}

			//对参数名按照ASCII升序排序
			Object[] key = map.keySet().toArray();
			Arrays.sort(key);

			//生成加密原串  
			StringBuffer res = new StringBuffer(128);
			for (int i = 0; i < key.length; i++) {
				res.append(key[i] + "=" + map.get(key[i]) + "&");
			}

			String rStr = res.substring(0, res.length() - 1);
			logger.info("请求接口加密原串 = " + rStr);
			if (appInitKey == null) {
				return rStr + "&hmac=" + getKeyedDigestUTF8(rStr, "");
			}

			return rStr + "&hmac=" + getKeyedDigestUTF8(rStr, appInitKey);
		} catch (Exception e) {
			e.printStackTrace();
		}

		return null;
	}
	
	public String getKeyedDigest(String strSrc, String key) {
		try {
			MessageDigest md5 = MessageDigest.getInstance("MD5");
			md5.update(strSrc.getBytes("GBK"));

			String result = "";
			byte[] temp;
			temp = md5.digest(key.getBytes("GBK"));
			for (int i = 0; i < temp.length; i++) {
				result += Integer.toHexString((0x000000ff & temp[i]) | 0xffffff00).substring(6);
			}
			return result;
		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace();
		} catch (Exception e) {
			e.printStackTrace();
		}

		return null;
	}

	public String submitPost(String url, String params) {
		StringBuffer responseMessage = null;
		java.net.HttpURLConnection connection = null;
		java.net.URL reqUrl = null;
		OutputStreamWriter reqOut = null;
		InputStream in = null;
		BufferedReader br = null;
		int charCount = -1;
		try {
			responseMessage = new StringBuffer(128);
			reqUrl = new java.net.URL(url);
			connection = (java.net.HttpURLConnection) reqUrl.openConnection();
			connection.setReadTimeout(5000);
			connection.setConnectTimeout(5000);
			connection.setDoOutput(true);
			connection.setDoInput(true);
			connection.setRequestMethod("POST");
			reqOut = new OutputStreamWriter(connection.getOutputStream(),"UTF-8");
			reqOut.write(params);
			reqOut.flush();

			in = connection.getInputStream();
			br = new BufferedReader(new InputStreamReader(in, "UTF-8"));
			while ((charCount = br.read()) != -1) {
				responseMessage.append((char) charCount);
			}
		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			try {
				if (in != null) {
					in.close();
				}
				if (reqOut != null) {
					reqOut.close();
				}
			} catch (Exception e) {
				e.printStackTrace();
			}
		}

		return responseMessage.toString();
	}
	
	public static String getKeyedDigestUTF8(String strSrc, String key) {
        try {
            MessageDigest md5 = MessageDigest.getInstance("MD5");
            md5.update(strSrc.getBytes("UTF8"));
            String result="";
            byte[] temp;
            temp=md5.digest(key.getBytes("UTF8"));
    		for (int i=0; i<temp.length; i++){
    			result+=Integer.toHexString((0x000000ff & temp[i]) | 0xffffff00).substring(6);
    		}
    		return result;
    		
        } catch (NoSuchAlgorithmException e) {
        	
        	e.printStackTrace();
        	
        }catch(Exception e)
        {
          e.printStackTrace();
        }
        return null;
    }
}


