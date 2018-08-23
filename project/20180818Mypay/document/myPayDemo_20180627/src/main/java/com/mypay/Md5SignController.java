package com.mypay;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.logging.log4j.LogManager;
import org.apache.logging.log4j.Logger;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

/**
 * Servlet implementation class CgController
 */
@WebServlet(urlPatterns = {"/md5Sign"})
public class Md5SignController extends HttpServlet {

	private static final long serialVersionUID = 1L;

	private static final Logger LOG = LogManager.getLogger(Md5SignController.class);

	/**
	 * 返回MD5
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response)
		throws ServletException, IOException {
		response.setCharacterEncoding("UTF-8");
		response.setContentType("application/json");
		//

		String amount = request.getParameter("amount");
		String clientIp = request.getParameter("clientIp");
		String merId = request.getParameter("merId");
		String merchantNo = request.getParameter("merchantNo");
		String notifyUrl = request.getParameter("notifyUrl");
		String payType = request.getParameter("payType");
		String terminalClient = request.getParameter("terminalClient");
		String tradeDate = request.getParameter("tradeDate");

		// language=JSON
		String versidddon = "";
		StringBuffer signInfo = new StringBuffer();
		signInfo.append("amount=" + amount);
		signInfo.append("&clientIp=" + clientIp);
		signInfo.append("&merId=" + merId);
		signInfo.append("&merchantNo=" + merchantNo);

		signInfo.append("&notifyUrl=" + notifyUrl);
		signInfo.append("&payType=" + payType);
		signInfo.append("&terminalClient=" + terminalClient);
		signInfo.append("&tradeDate=" + tradeDate);
		signInfo.append("&key=" + Constants.md5Key);

		String signString = signInfo.toString();
		LOG.debug("要签名的资讯：" + signString);
		// ----------------------------------
		// ----------------------------------
		// ----------------------------------
		// ----------------------------------
		// ----------------------------------

		String sign = Util.md5(signString);
		LOG.debug("签名：" + sign);

		Map<String, Object> returnValues = new HashMap<>();
		returnValues.put("sign", sign);

		Gson gson = new GsonBuilder().setDateFormat("yyyy-MM-dd'T'HH:mm:ss.SSS'Z'").create();

		String returnString = gson.toJson(returnValues);
		PrintWriter writer = response.getWriter();
		writer.write(returnString);
		writer.flush();
		writer.close();

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
