package com.mypay;

import java.io.IOException;
import java.io.PrintWriter;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.logging.log4j.LogManager;
import org.apache.logging.log4j.Logger;

/**
 * Servlet implementation class CgController
 */
@WebServlet(urlPatterns = {"/notifyUrl"})
public class NotifyUrlController extends HttpServlet {

	private static final long serialVersionUID = 1L;

	private static final Logger LOG = LogManager.getLogger(NotifyUrlController.class);

	/**
	 * Default constructor.
	 */
	public NotifyUrlController() {
		// TODO Auto-generated constructor stub
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response)
		throws ServletException, IOException {

		String amount = request.getParameter("amount");
		String merId = request.getParameter("merId");
		String merchantNo = request.getParameter("merchantNo");
		String orderNo = request.getParameter("orderNo");
		String tradeDate = request.getParameter("tradeDate");
		String realAmount = request.getParameter("realAmount");

		StringBuffer signInfo = new StringBuffer();
		signInfo.append("amount=" + amount);
		signInfo.append("&merId=" + merId);
		signInfo.append("&merchantNo=" + merchantNo);
		signInfo.append("&orderNo=" + orderNo);
		signInfo.append("&realAmount=" + realAmount);
		signInfo.append("&tradeDate=" + tradeDate);
		signInfo.append("&key=" + Constants.md5Key);

		String signString = signInfo.toString();
		LOG.info(signString);

		String mySign = Util.md5(signString);
		LOG.info("mySign = " + mySign);

		String sign = request.getParameter("sign");
		String clientIp = request.getParameter("clientIp");
		String extra = request.getParameter("extra");
		LOG.info("sign = " + sign);
		LOG.info("clientIp = " + clientIp);
		LOG.info("extra = " + extra);

		PrintWriter out = response.getWriter();

		if (mySign.equals(sign)) {
			LOG.info("验签成功");
			out.write("MYPAY");// 必须返回
			return;
		} else {
			LOG.info("验签失败");
			out.write("fail~~~~~~");
			return;
		}

	}

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response)
		throws ServletException, IOException {
		doGet(request, response);
	}

}
