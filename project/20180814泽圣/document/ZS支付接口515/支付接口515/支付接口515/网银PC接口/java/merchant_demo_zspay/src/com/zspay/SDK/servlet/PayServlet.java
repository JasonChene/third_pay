package com.zspay.SDK.servlet;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.security.NoSuchAlgorithmException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.Set;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import com.alibaba.fastjson.JSONObject;
import com.zspay.SDK.utilApi.MD5Encrypt;

@SuppressWarnings("serial")
public class PayServlet extends HttpServlet {
	public void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException, ServletException {

		JSONObject json = new JSONObject();
		String merchantCode="1000000001";//商户号
		String md5key="123456ADSEF";//md5key
		String payUrl="";//正式提交地址
		String outOrderId=request.getParameter("outOrderId");// 商户系统订单号
		String totalAmount=request.getParameter("totalAmount");// 交易金额 单位分
		String orderCreateTime=new SimpleDateFormat("yyyyMMddHHmmss").format(new Date());// 订单创建时间：格式：yyyyMMddHHmmss
		String lastPayTime="20181205141555"; //最晚支付时间  格式;yyyyMMddHHmmss
		json.put("merchantCode",merchantCode );
		json.put("outOrderId",outOrderId );		
		json.put("totalAmount", totalAmount);
		json.put("orderCreateTime",orderCreateTime );
		json.put("lastPayTime",lastPayTime);
		String signsrc = String.format("lastPayTime=%s&merchantCode=%s&orderCreateTime=%s&outOrderId=%s&totalAmount=%s&KEY=%s",
				lastPayTime,merchantCode,orderCreateTime,outOrderId,totalAmount, md5key);
		String sign = "";
		try {
			sign = MD5Encrypt.getMessageDigest(signsrc);
		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace();
		}
		json.put("sign", sign);
		String gn = encodeChange(request.getParameter("goodsName"));
		String ge = encodeChange(request.getParameter("goodsExplain"));
		json.put("goodsName", gn);
		json.put("goodsExplain", ge);
		json.put("merUrl", request.getParameter("merUrl"));
		json.put("noticeUrl", request.getParameter("noticeUrl"));
		json.put("bankCode", request.getParameter("bankCode"));
		json.put("bankCardType", request.getParameter("bankCardType"));// 01纯借记,00借贷记综合,03企业网银
		json.put("ext", request.getParameter("ext"));
		//跳转到中转界面
		Set<String> set = json.keySet();
		Iterator<String> it = set.iterator();
		Map<String, String> map = new HashMap<String, String>();
		while (it.hasNext()) {
			String key = it.next();
			map.put(key, json.getString(key));
		}
		request.setAttribute("paramMap", map);
		request.setAttribute("payUrl",payUrl);
		response.setContentType("text/html;charset=UTF-8");
		request.getRequestDispatcher("/payMac.jsp").forward(request, response);
	}

	public String encodeChange(String str) throws UnsupportedEncodingException {
		if (str.isEmpty()) {
			str = "";
		} else {
			// 将字符由iso编码转为UTF-8
			str = new String(str.getBytes("ISO-8859-1"), "UTF-8");
		}
		return str;
	}
}
