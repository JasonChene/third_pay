package com.kfu.pay.servlet;

import java.io.IOException;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.alibaba.fastjson.JSONObject;
import com.mbpay.pay.utils.PayUtils;

/**
 * 订单查询接口 
 */

public class QueryOrderApiServlet extends HttpServlet {
	private static final long serialVersionUID = 1L;

	public QueryOrderApiServlet() {
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
        String merKey = ""; // 商户密钥
        String mbOrderId = request.getParameter("mbOrderId"); //平台流水号
        long time = System.currentTimeMillis()/1000; // 时间戳
        JSONObject json = new JSONObject();
        json.put("merAccount", merAccount);
        json.put("mbOrderId", mbOrderId);
        json.put("time", time);
        String sign = PayUtils.buildSign(json,merKey);
        json.put("sign", sign);
        String data = PayUtils.buildData(json,merKey);
        JSONObject result = PayUtils.httpGet("", merAccount, data);
        System.out.println(result.toJSONString());

		//进行业务处理
        if("000000".equals(result.getString("code"))){
    		request.setAttribute("responseDataMap", result.get("data"));
        }
		RequestDispatcher view	= request.getRequestDispatcher("jsp/queryOrderApiResponse.jsp");
		view.forward(request, response);
	}

	public String formatStr(String text) {
		return (text == null) ? "" : text.trim();
	}

}
