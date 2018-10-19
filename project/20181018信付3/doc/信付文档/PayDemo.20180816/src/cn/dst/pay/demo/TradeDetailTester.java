package cn.dst.pay.demo;

import java.util.HashMap;
import java.util.Map;

/**
 * 交易记录详情
 */
public class TradeDetailTester extends BaseTester {

	public static void main(String args[]) {
		try {
			Map<String, String> params = new HashMap<>();
			params.put("merchantOrderNo", "20180615044031333511491021778433");
			System.out.println(get("/trade/detail.do", params));
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
}