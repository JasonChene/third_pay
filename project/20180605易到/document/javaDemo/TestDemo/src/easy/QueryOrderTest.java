package easy;

import java.util.HashMap;
import java.util.Map;

import org.apache.commons.codec.binary.Base64;

import net.sf.json.JSONObject;
import util.EncodeUtil;
import util.HttpHelper;
import util.HttpResponse;
import util.JsonUtil;
import util.MD5;
import util.TLinx2Util;

import com.kspay.AESUtil;
import com.kspay.BASEUtil;

public class QueryOrderTest {
	public static void main(String[] args) throws Exception {
		getOrder();
	}
	
	
	public static void getOrder() throws Exception{
		String key = "8UN3F268SXLFYLLC";
		String TradeCode = "AZBm4ggeLkZ5WWtf";
		String mechantCode = "MERCOONT201707111111232";
		String url = "http://api.easypay188.com/merExternalCommon/getmerorder.do";
		Map<String, String> map = new HashMap<String, String>();
		map.put("tradeCode", "QueryOrder"); 
		map.put("version", "1.0.1"); 
		map.put("merchOrderId", "order1524560474679");
		
		String sort = TLinx2Util.sort(map);
		System.out.println("sort:"+sort);
		String sortPrivage = Base64.encodeBase64String(sort.getBytes());
		String aesPrivage = AESUtil.encrypt(sortPrivage, key);
		String mdvage = MD5.GetMD5Code(aesPrivage+TradeCode).toUpperCase();//以md5加密 加上TradeCode转换大写
		System.out.println(mdvage);
		map.put("sign", mdvage);//sign不参与平台验签
		
		String resMsg = EncodeUtil.getUrlStr(map);
		System.out.println(resMsg);
		
		
		String Keyprivage = BASEUtil.encode(resMsg);
		System.out.println(Keyprivage);
		
		String transData = AESUtil.encrypt(Keyprivage, key);
		System.out.println("AES："+transData);
		
		Map<String,String> reqMap = new HashMap<String,String>();
		reqMap.put("merchantCode", mechantCode);
		reqMap.put("transData",  transData);
		reqMap.put("TradeCode",  TradeCode);
		String reqStr = "reqJson="+JsonUtil.map2Json(reqMap);
		System.out.println(reqStr);
		HttpResponse resp = HttpHelper.doHttp(url, "POST","UTF-8", reqStr, "600000");
		String body = resp.getBody();
		
		System.out.println(body);
	}
}
