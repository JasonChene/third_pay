package demo;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

public class Test {

	public static void main(String[] args) throws Exception {
		String partner = "";//商户号
		String key = "";//商户密钥
		Map<String,String> map = new HashMap<String,String>();
		map.put("payType", "syt");//接口类型(syt:收银台)
		map.put("partner", partner);//商户号
		map.put("orderId", new SimpleDateFormat("yyyyMMddHHmmssSSS").format(new Date()));//商户订单号
		map.put("orderAmount", "1");//商户订单金额(金额最小0.01)
		map.put("version", "1.0");//接口版本(1.0)
		map.put("signType", "MD5");//签名方式(MD5)
		map.put("payMethod", "22");//支付方式:11：微信 22：支付宝33:QQ支付  之前的支付宝用22，新PC端用23，新手机端用24
		map.put("notifyUrl", "http://www.baidu.com");//异步回调地址
		//待签名字符串
		String param = YsfUtil.getSignStr(map, key);
		//签名
		String sign = YsfUtil.md5UTF8(param).toUpperCase();
		map.put("sign", sign);
		//组装请求url,用户使用app或者浏览器打开该链接即可唤起支付宝app支付
		String reqUrl = YsfUtil.getReqUrl(map,"http://qr.sytpay.cn/api/v1/create.php");
		System.out.println(reqUrl);
		
	}
}
