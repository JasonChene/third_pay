package com.zspay.netbank.demo.servlet;

import java.io.IOException;
import java.util.Arrays;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.alibaba.fastjson.JSONObject;
import com.zspay.netbank.demo.utils.DateUtil;
import com.zspay.netbank.demo.utils.MD5Encrypt;
import com.zspay.netbank.demo.utils.Rsa;
import com.zspay.netbank.demo.utils.StringUtil;


public class NetbankServlet extends HttpServlet {
	private static final long serialVersionUID = 1L;
	private static final Object EQUAL = "=";

	/**
	 * @see HttpServlet#HttpServlet()
	 */
	public NetbankServlet() {
		super();
	}

	/**
	 * get请求
	 */
	protected void doGet(HttpServletRequest request,
			HttpServletResponse response) throws ServletException, IOException {
		this.doPost(request, response);
	}

	/**
	 * post请求
	 */
	protected void doPost(HttpServletRequest request,
			HttpServletResponse response) throws ServletException, IOException {
		// 对支付所需参数进行赋值
		String merchantCode = "1000000001";// 商户的商户号
		String outUserId = "";// 商户的会员ID
		String notifyUrl = "";// 支付成功后通知商户的地址
		String latestPayTime = "20151104102334";// 最晚支付时间
												// （需要yyyyMMddHHmmss格式的时间字符串）
		String merUrl = "http://192.168.13.17:8080/zlinepay/rec.jsp";// 商户取货地址(必传)
		String randomStr = randomStr();// 随机字符串
		String merchantOrderTime = DateUtil.toStr("yyyyMMddHHmmss");// 下单时间（需要yyyyMMddHHmmss格式的时间字符串）
		String goodsName = StringUtil.getNewString(request
				.getParameter("goodsName"));// 商品名称
		String goodsDescription = StringUtil.getNewString(request
				.getParameter("goodsDescription"));// 商品描述
		String outOrderId = request.getParameter("outOrderId");// 商户订单号
		Long totalAmount = Long.parseLong(request.getParameter("totalAmount"));// 商品金额（单位：分）
		String ext = "";// 扩展字段
		/**
		 * 把生成密钥所需的参数放入到map中，并调用生成密钥的方法
		 */
		String sign = "";// 密钥
		Map<String, Object> parmMaps = new HashMap<String, Object>();
		parmMaps.put("merchantCode", merchantCode);
		parmMaps.put("notifyUrl", notifyUrl);
		parmMaps.put("outOrderId", outOrderId);
		parmMaps.put("totalAmount", totalAmount);
		parmMaps.put("outUserId", outUserId);
		parmMaps.put("merchantOrderTime", merchantOrderTime);
		sign = sign(parmMaps, "MD5", "123456ADSEF");// 生成签名
		/**
		 * 商户支付的参数放入request,便于转发页面对表单赋值
		 */
		request.setAttribute("merchantCode", merchantCode);
		request.setAttribute("outOrderId", outOrderId);
		request.setAttribute("totalAmount", totalAmount);
		request.setAttribute("notifyUrl", notifyUrl);
		request.setAttribute("merchantOrderTime", merchantOrderTime);
		request.setAttribute("latestPayTime", latestPayTime);
		request.setAttribute("ext", ext);
		request.setAttribute("outUserId", outUserId);
		request.setAttribute("merUrl", merUrl);
		request.setAttribute("goodsName", goodsName);
		request.setAttribute("goodsDescription", goodsDescription);
		request.setAttribute("randomStr", randomStr);
		request.setAttribute("sign", sign);
		response.setContentType("text/html;charset=UTF-8");
		// 由于不能直接用后台程序跳入到HTML5页面所以先跳转到一个过渡jsp页面，在该页面自动已表单的方式提交给支付网页控件
		request.getRequestDispatcher("/forward.jsp").forward(request, response);
	}

	/**
	 * 
	 * @Description: 生成密钥
	 * @param orderMaps
	 *            需要加入验签的参数
	 * @param type
	 *            验签类型：MD5、RSA
	 * @param key
	 *            Key值
	 * @return
	 * 
	 */
	public String sign(Map<String, Object> orderMaps, String type, String key) {
		String genSign = "";
		try {
			String[] signFields = new String[6];
			signFields[0] = "merchantCode";
			signFields[1] = "notifyUrl";
			signFields[2] = "outOrderId";
			signFields[3] = "totalAmount";
			signFields[4] = "outUserId";
			signFields[5] = "merchantOrderTime";
			JSONObject param = (JSONObject) JSONObject.toJSON(orderMaps);
			String signSrc = orgSignSrc(signFields, param);
			if ("MD5".equals(type)) {
				// MD5的方式签名
				signSrc += "&KEY=" + key;
				genSign = MD5Encrypt.getMessageDigest(signSrc);
			} else if ("Rsa".equals(type)) {
				// RSA的方式签名
				Rsa r = new Rsa();
				String src = r.orgSignSrc(signFields, param);
				genSign = r.sign(src, key);
			} else {
				return "";
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		return genSign;
	}

	/**
	 * 构建签名原文
	 * 
	 * @param signFilds
	 * @param param
	 * @return
	 */
	private static String orgSignSrc(String[] signFields, JSONObject param) {
		if (signFields != null) {
			Arrays.sort(signFields); // 对key按照 字典顺序排序
		}

		StringBuffer signSrc = new StringBuffer("");
		int i = 0;
		for (String field : signFields) {
			signSrc.append(field);
			signSrc.append(EQUAL);
			signSrc.append((StringUtil.isEmpty(param.getString(field)) ? ""
					: param.getString(field)));
			// 最后一个元素后面不加&
			if (i < (signFields.length - 1)) {
				signSrc.append("&");
			}
			i++;
		}
		return signSrc.toString();
	}

	public String randomStr() {
		char[] chars = { '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A',
				'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
				'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y',
				'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
				'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w',
				'x', 'y', 'z' };
		String res = "";
		for (int i = 0; i < 30; i++) {
			int id = (int) Math.ceil(Math.random() * 60);
			res += chars[id];
		}
		return res;
	}
}
