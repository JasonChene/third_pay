package com.suncity.pay.model;

import javax.servlet.http.HttpServletRequest;

import com.suncity.pay.utils.MD5Utils;

/**
 * 发起支付参数model
 * @author Terry
 */
public class AppPayModel
{
    /**
     * 商户ID
     */
    private String p1_mchtid;

    /**
     * 支付方式
     */
    private String p2_paytype;

    /**
     * 支付金额
     */
    private String p3_paymoney;

    /**
     * 订单号码
     */
    private String p4_orderno;

    /**
     * 異步通知地址
     */
    private String p5_callbackurl;

    /**
     * 同步跳轉地址
     */
    private String p6_notifyurl;

    /**
     * 版本號
     */
    private String p7_version;

    /**
     * 加密類型
     */
    private String p8_signtype;

    /**
     * 備注信息
     */
    private String p9_attach;

    /**
     * 分成標識
     */
    private String p10_appname;

    /**
     * 是否顯示收銀檯
     */
    private String p11_isshow;

    /**
     * 商戶用戶下單IP
     */
    private String p12_orderip;

    /**
     * 加密密鑰
     */
    private String safetyKey;

    /**
     * 構造器，讀取request對象中對應的參數，注入model
     * @param request 请求消息体对象
     */
    public AppPayModel(HttpServletRequest request)
    {
        super();
        this.p1_mchtid = request.getParameter("p1_mchtid");
        this.p2_paytype = request.getParameter("p2_paytype");
        this.p3_paymoney = request.getParameter("p3_paymoney");
        this.p4_orderno = request.getParameter("p4_orderno");
        this.p5_callbackurl = request.getParameter("p5_callbackurl");
        this.p6_notifyurl = request.getParameter("p6_notifyurl");
        this.p7_version = request.getParameter("p7_version");
        this.p8_signtype = request.getParameter("p8_signtype");
        this.p9_attach = request.getParameter("p9_attach");
        this.p10_appname = request.getParameter("p10_appname");
        this.p11_isshow = request.getParameter("p11_isshow");
        this.p12_orderip = request.getParameter("p12_orderip");
        this.safetyKey = request.getParameter("safetyKey");
    }

    /**
     * 根據當前對象屬性生成簽名
     * @return 返回參數生成簽名
     * @author Terry
     */
    public String getSign()
    {
        return MD5Utils.enCode(this.toString());
    }

    @Override
    public String toString()
    {
        StringBuffer sb = new StringBuffer();
        sb.append("p1_mchtid=").append(p1_mchtid);
        sb.append("&p2_paytype=").append(p2_paytype);
        sb.append("&p3_paymoney=").append(p3_paymoney);
        sb.append("&p4_orderno=").append(p4_orderno);
        sb.append("&p5_callbackurl=").append(p5_callbackurl);
        sb.append("&p6_notifyurl=").append(p6_notifyurl);
        sb.append("&p7_version=").append(p7_version);
        sb.append("&p8_signtype=").append(p8_signtype);
        sb.append("&p9_attach=").append(p9_attach);
        sb.append("&p10_appname=").append(p10_appname);
        sb.append("&p11_isshow=").append(p11_isshow);
        sb.append("&p12_orderip=").append(p12_orderip);
        sb.append(safetyKey);
        return sb.toString();
    }
}