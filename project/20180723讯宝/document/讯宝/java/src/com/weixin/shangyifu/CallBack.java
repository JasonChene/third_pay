package com.weixin.shangyifu;

import java.io.IOException;
import java.io.PrintWriter;
import java.security.NoSuchAlgorithmException;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.weixin.utils.Utility;

/**
 * 同步回调
 * @author xyx
 *
 */
@WebServlet("/callback")
public class CallBack extends HttpServlet {
	private static final long serialVersionUID = 1L;

	@Override
	protected void doGet(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
			doPost(req,resp);
	}
	
	@Override
	protected void doPost(HttpServletRequest req, HttpServletResponse resp)
			throws ServletException, IOException {
		PrintWriter out = resp.getWriter();
		resp.setContentType("text/html;charset=utf-8");
		String key = "be8c2fadfb764e169f5a59b4315d0889";
		String orderid = req.getParameter("orderid");//商户订单号
		String opstate = req.getParameter("opstate");//订单结果
		String ovalue = req.getParameter("ovalue");//订单结果
		String sysorderid = req.getParameter("sysorderid");//商易付订单号
		String systime = req.getParameter("systime");//商易付订单时间
		try {
			String sign = Utility.Md5Encrypt("orderid="+orderid+"&opstate="+opstate+"&ovalue="+ovalue+"&time="+systime+"&sysorderid="+sysorderid+key);
			System.out.println("sign:"+sign);
			System.out.println("req.getParameter(sign):--->"+req.getParameter("sign"));
			if(req.getParameter("sign") == sign) {
				System.out.println(111);
				out.write("opstate=0");
			}
		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace();
		}
	}
}
