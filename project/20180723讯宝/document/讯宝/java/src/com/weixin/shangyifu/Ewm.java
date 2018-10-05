package com.weixin.shangyifu;

import java.io.IOException;
import java.security.NoSuchAlgorithmException;
import java.util.HashMap;
import java.util.Map;
import java.util.UUID;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.weixin.utils.Utility;

/**
 * 扫码支付：该通道包括支付宝扫码、银联扫码、QQ扫码、微信扫码、京东扫码。商户系统发起网关支付请求，
 * 网关跳转到对应二维码页面。无同步返回值。 
 *
 */

@WebServlet("/ewm")
public class Ewm extends HttpServlet{
	private static final long serialVersionUID = 1L;

	@Override
	protected void doGet(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
			doPost(req,resp);
	}
	
	@Override
	protected void doPost(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
		req.setCharacterEncoding("utf-8");
		String payUrl = "http://gateway.xunbaopay9.com/chargebank.aspx";
		String key = "be8c2fadfb764e169f5a59b4315d0889";
		String parter = "1275";//商户ID
		String type = "8012";//银行编号
		String value = "3.00";//交易金额
		String orderid = UUID.randomUUID().toString().replaceAll("-", "").substring(0, 30);//获取30位以内的随机订单号
		String callbackurl = "http://xdclass.tunnel.qydev.com/shangyifu/callback";//回调同步地址
		Map<String, String> map = new HashMap<String, String>();
		map.put("parter", parter);
		map.put("type", type);
		map.put("value",value);
		map.put("orderid", orderid);//获取30位以内的随机订单号
		map.put("callbackurl", callbackurl);//回调同步地址
		try {
			String sign = Utility.Md5Encrypt("parter="+parter+"&type="+type+"&value="+value+"&orderid="+orderid+"&callbackurl="+callbackurl + key);
			map.put("hrefbackurl", "http://www.shangyizhifu.com");
			map.put("payerIp", "0.0.0.0");
			map.put("attach", "");
			map.put("agent", "");
			map.put("sign", sign);
			resp.sendRedirect(payUrl+"?"+Utility.CreateSign(map));
		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace();
		}
		
	}
}
