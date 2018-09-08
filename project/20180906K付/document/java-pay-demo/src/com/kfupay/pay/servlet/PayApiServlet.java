package com.kfu.pay.servlet;

import java.io.IOException;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.alibaba.fastjson.JSONObject;
import com.mbpay.pay.utils.PayUtils;

/**
 * 支付接口 
 */

@WebServlet("/PayApiServlet")
public class PayApiServlet extends HttpServlet {
	private static final long serialVersionUID = 1L;

	public PayApiServlet() {
		super();
	}

	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		doPost(request, response);
	}

	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		//UTF-8编码
		request.setCharacterEncoding("UTF-8");
		response.setCharacterEncoding("UTF-8");
		response.setContentType("text/html");
		String merAccount = ""; // 商户标识
        String merNo = ""; // 商户编号
        String merKey = "0e597cde0bb04a0cad0838bb321d0ega"; // 商户密钥
        String orderId = request.getParameter("orderId"); // 商户订单号
        String amount = request.getParameter("amount"); //支付金额
        String productType = request.getParameter("productType"); //商品类别码 1
        String product = request.getParameter("product"); //商品名称
        String productDesc = request.getParameter("productDesc"); // 商品描述
        String userType = request.getParameter("userType"); // 用户类型
        String payWay = request.getParameter("payWay");
        String payType = request.getParameter("payType");// 支付类型
        String userId = request.getParameter("userId"); // 用户标识
        String appId = request.getParameter("appId"); // 微信公众号
        String userIp = request.getParameter("userIp"); //用户IP地址
        String returnUrl = request.getParameter("returnUrl"); //
        String notifyUrl = request.getParameter("notifyUrl"); //
        JSONObject json = new JSONObject();
        json.put("merAccount", merAccount);
        json.put("merNo", merNo);
        json.put("orderId", orderId);
        json.put("time", System.currentTimeMillis()/1000);
        json.put("amount", amount);
        json.put("productType", productType);
        json.put("product", product);
        json.put("productDesc", productDesc);
        json.put("userType", userType);
        json.put("payWay", payWay);
        json.put("payType", payType);
        json.put("userId", userId);
        json.put("appId", appId);
        json.put("userIp", userIp);
        json.put("returnUrl", returnUrl);
        json.put("notifyUrl", notifyUrl);
        String sign = PayUtils.buildSign(json,merKey);
        json.put("sign", sign);
        String data = PayUtils.buildData(json,merKey);
        JSONObject result = PayUtils.httpGet("", merAccount, data);
        System.out.println(result.toJSONString());

		//进行业务处理
        if("000000".equals(result.getString("code"))){
    		request.setAttribute("responseDataMap", result.get("data"));
        }
		RequestDispatcher view	= request.getRequestDispatcher("jsp/payApiResponse.jsp");
		view.forward(request, response);
	}

	public String formatStr(String text) {
		return (text == null) ? "" : text.trim();
	}

}
