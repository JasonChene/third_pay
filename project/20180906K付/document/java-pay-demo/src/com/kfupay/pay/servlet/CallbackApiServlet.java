package com.kfu.pay.servlet;

import java.io.IOException;
import java.io.PrintWriter;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.alibaba.fastjson.JSONObject;
import com.mbpay.pay.utils.PayUtils;

/**
 * 回调 
 */

public class CallbackApiServlet extends HttpServlet {
	private static final long serialVersionUID = 1L;

	public CallbackApiServlet() {
		super();
	}

	//一键支付的页面回调方式为GET
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		//UTF-8编码
		request.setCharacterEncoding("UTF-8");
		response.setCharacterEncoding("UTF-8");
		response.setContentType("text/html");
		PrintWriter out	= response.getWriter();

		String merAccount = request.getParameter("merAccount"); // 商户标识
        String data = request.getParameter("data"); //数据
        String merKey = "";
    	JSONObject json = PayUtils.decrypt(data, merKey);

		request.setAttribute("dataMap", json);
		System.out.println(json.toJSONString());
		//回写SUCCESS
		out.println("SUCCESS");
		out.flush();
		out.close();
	}
	
	//一键支付的后台回调方式为POST
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		//UTF-8编码
		request.setCharacterEncoding("UTF-8");
		response.setCharacterEncoding("UTF-8");
		response.setContentType("text/html");
		PrintWriter out	= response.getWriter();

		String merAccount = request.getParameter("merAccount"); // 商户标识
        String data = request.getParameter("data"); //数据
        String merKey = "";
    	JSONObject json = PayUtils.decrypt(data, merKey);

		request.setAttribute("dataMap", json);
		System.out.println(json.toJSONString());

		//回写SUCCESS
		out.println("SUCCESS");
		out.flush();
		out.close();
	}

	public String formatStr(String text) {
		return (text == null) ? "" : text.trim();
	}

}
