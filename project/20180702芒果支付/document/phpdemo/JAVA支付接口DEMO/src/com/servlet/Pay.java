package com.servlet;

import java.io.PrintWriter;
import java.text.SimpleDateFormat;
import java.util.TreeMap;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import net.sf.json.JSONObject;

public class Pay extends HttpServlet {

	private static final long serialVersionUID = 1L;

	public void doGet(HttpServletRequest req, HttpServletResponse res) {
		try {
			SimpleDateFormat df = new SimpleDateFormat("yyyyMMddHHmmss");
			String downSn = "test" + df.format(System.currentTimeMillis()).toString();
			PrintWriter out = res.getWriter();
			JSONObject content = new JSONObject();
			TreeMap<String, String> obj = new TreeMap<String, String>();
			obj.put("type_code", "zfbh5");
			obj.put("subject", "test");
			obj.put("amount", "1");
			obj.put("agent_type", "1");
			obj.put("notify_url", "http://yourdomain");
			obj.put("down_sn", downSn);
			// 生成签名
			StringBuffer paramstr = new StringBuffer();
			for (String pkey : obj.keySet()) {
				String pvalue = obj.get(pkey);
				if (pvalue != null && pvalue != "") {
					paramstr.append(pkey + "=" + pvalue + "&");
				}
			}
			String paramsrc = paramstr.substring(0, paramstr.length() - 1);
			obj.put("sign", sign(paramsrc));
			out.println("obj = " + obj.toString());
			JSONObject jsonObject = JSONObject.fromObject(obj);
			String jsonStr = jsonObject.toString();
			byte[] queryBytes;
			queryBytes = RSAUtils.encryptByPublicKey(jsonStr.getBytes("UTF-8"), PayUtil.pubkey);
			String cipherData = Base64Utils.encode(queryBytes);
			content.put("member_code", PayUtil.memberCode);
			content.put("cipher_data", cipherData);

			out.println("content = " + content.toString());
			out.println("jsonStr = " + jsonStr);

			String str = HttpClientUtil.submitPost(PayUtil.url + "/api/trans/pay",
					"member_code=" + PayUtil.memberCode + "&cipher_data=" + cipherData, "utf-8", 60000, 60000);

			out.println("result = " + str);
		} catch (Exception e) {
			e.printStackTrace();
		}

	}

	private static String sign(String paramSrc) {
		StringBuffer strbuff = new StringBuffer();
		strbuff.append(paramSrc + "&key=" + PayUtil.memberSecret);
		String sign = null;
		try {
			sign = Md5Util.MD5Encode(strbuff.toString(), "UTF-8");
			System.out.println("签名:" + sign);
		} catch (Exception e1) {
			e1.printStackTrace();
		}
		return sign;
	}
}
