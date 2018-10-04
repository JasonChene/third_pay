package com.zhifu.pay.dto;


public class QueryOrderReqDto extends AbstractRequestDTO {
	/**
	 * 
	 */
	private static final long serialVersionUID = 6345298250978275785L;
	
	private String orderNo;   //商户订单号
	
	public String getOrderNo() {
		return orderNo;
	}

	public void setOrderNo(String orderNo) {
		this.orderNo = orderNo;
	}
	
}
