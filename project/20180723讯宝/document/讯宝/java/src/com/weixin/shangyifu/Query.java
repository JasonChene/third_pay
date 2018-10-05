package com.weixin.shangyifu;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.URL;
import java.net.URLConnection;
import java.security.NoSuchAlgorithmException;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.weixin.utils.Utility;

@WebServlet("/query")
public class Query extends HttpServlet{
	private static final long serialVersionUID = 1L;

	@Override
	protected void doGet(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
			doPost(req,resp);
	}
	
	@Override
	protected void doPost(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
		String payUrl = "http://gateway.xunbaopay9.com/Search.aspx";
        String key = "be8c2fadfb764e169f5a59b4315d0889";
        String parter = "1275";//商户ID
        String orderid = "9bdba0c206a649ca90d76b06976e18";//需查询的商户系统订单号
		try {
			String sign = Utility.Md5Encrypt("orderid="+orderid+"&parter="+parter)+key;
			resp.sendRedirect(payUrl+"?"+"orderid="+orderid+"&parter="+parter+"&sign="+sign);
			
			StringBuffer sb = new StringBuffer();
			InputStreamReader isr = null;
			BufferedReader br = null;
				//建立网络连接
				URL url = new URL(payUrl);
				//打开网络连接
				URLConnection uc = url.openConnection();
				//下载
				isr = new InputStreamReader(uc.getInputStream(),"utf-8");
				//缓冲
				br = new BufferedReader(isr);
				//创建一个临时文件
				String temp="";
				//拿到所有的
				while((temp=br.readLine())!=null){
					sb.append(temp);
				}
				br.close();
				isr.close();
			PrintWriter out = resp.getWriter();
			out.write(sb.toString());			
		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace();
		}
	}
}
