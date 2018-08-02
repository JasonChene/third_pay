package com.demo.bean;

import com.demo.util.IgnoreSign;

import java.util.HashMap;
import java.util.Map;

/**
 * @author kogome
 */
public class QueryOrderResponseBean {
    private String code;
    private String msg;
    private String date;
    private String merchantNo;
    private String orderStatus;
    private String orderStatusDesc;
    @IgnoreSign
    private String sign;

    public String getCode() {
        return code;
    }

    public void setCode(String code) {
        this.code = code;
    }

    public String getMsg() {
        return msg;
    }

    public void setMsg(String msg) {
        this.msg = msg;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getMerchantNo() {
        return merchantNo;
    }

    public void setMerchantNo(String merchantNo) {
        this.merchantNo = merchantNo;
    }

    public String getOrderStatus() {
        return orderStatus;
    }

    public void setOrderStatus(String orderStatus) {
        this.orderStatus = orderStatus;
    }

    public String getOrderStatusDesc() {
        return orderStatusDesc;
    }

    public void setOrderStatusDesc(String orderStatusDesc) {
        this.orderStatusDesc = orderStatusDesc;
    }

    public String getSign() {
        return sign;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }

    public Map toMap() {
        Map<String, Object> params = new HashMap<>(12);
        params.put("merchantNo",this.merchantNo);
        params.put("code",this.code);
        params.put("msg",this.msg);
        params.put("date",this.date);
        params.put("orderStatus",this.orderStatus);
        params.put("orderStatusDesc",this.orderStatusDesc);
        return params;
    }
}
