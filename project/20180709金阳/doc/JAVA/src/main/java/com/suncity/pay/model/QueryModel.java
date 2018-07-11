package com.suncity.pay.model;

import javax.servlet.http.HttpServletRequest;

import com.suncity.pay.utils.MD5Utils;

/**
 * 订单查询参数model
 * @author Terry
 */
public class QueryModel
{
    /**
     * 商户ID
     */
    private String p1_mchtid;

    /**
     * 加密方式
     */
    private String p2_signtype;

    /**
     * 订单号码
     */
    private String p3_orderno;

    /**
     * 版本号
     */
    private String p4_version;

    /**
     * 加密密钥
     */
    private String safetyKey;

    /**
     * 構造器，讀取request對象中對應的參數，注入model
     * @param request 请求消息体对象
     */
    public QueryModel(HttpServletRequest request)
    {
        super();
        this.p1_mchtid = request.getParameter("p1_mchtid");
        this.p2_signtype = request.getParameter("p2_signtype");
        this.p3_orderno = request.getParameter("p3_orderno");
        this.p4_version = request.getParameter("p4_version");
        String safetyKey;
        // 进入查询页面的时候这里是通过配置文件读取的
        Object tmpObj = request.getAttribute("safetyKey");
        if (tmpObj == null)
        {
            // 提交查询的时候这里是通过页面表单提交的
            safetyKey = request.getParameter("safetyKey");
            // 如果是表单提交的，设置一下到request对象里，因为查询玩结果再次返回页面的时候是通过getAttribute的方式取的
            request.setAttribute("safetyKey", safetyKey);
        }
        else
        {
            safetyKey = tmpObj.toString();
        }
        this.safetyKey = safetyKey;
    }

    /**
     * 根據當前對象屬性生成簽名
     * @return 返回參數生成簽名
     * @author Terry
     */
    public String getSign()
    {
        return MD5Utils.enCode(toString());
    }

    /**
     * 获取接口请求参数串
     * @return 返回请求查询接口的参数串
     * @author Terry
     */
    public String getParamString()
    {
        StringBuffer sb = new StringBuffer();
        sb.append("p1_mchtid=").append(p1_mchtid);
        sb.append("&p2_signtype=").append(p2_signtype);
        sb.append("&p3_orderno=").append(p3_orderno);
        sb.append("&p4_version=").append(p4_version);
        sb.append("&sign=").append(getSign());
        return sb.toString();
    }

    @Override
    public String toString()
    {
        StringBuffer sb = new StringBuffer();
        sb.append("p1_mchtid=").append(p1_mchtid);
        sb.append("&p2_signtype=").append(p2_signtype);
        sb.append("&p3_orderno=").append(p3_orderno);
        sb.append("&p4_version=").append(p4_version);
        sb.append(safetyKey);
        return sb.toString();
    }
}