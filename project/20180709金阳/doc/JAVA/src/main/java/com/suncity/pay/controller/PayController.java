package com.suncity.pay.controller;

import java.io.IOException;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;

import org.apache.log4j.Logger;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;

import com.suncity.pay.model.AppPayModel;
import com.suncity.pay.model.QueryModel;
import com.suncity.pay.service.PayService;

@Controller
public class PayController
{
    /**
     * 日志记录对象
     */
    private static final Logger LOG = Logger.getLogger(PayController.class);

    @Autowired
    private PayService payService;

    /**
     * 发起支付
     */
    @RequestMapping("/appPay")
    public String appPay(Map<String, String> map)
    {
        try
        {
            // 读取配置文件，通过map返回页面
            payService.readConfigFile(map);
        }
        catch (IOException e)
        {
            LOG.error("读取配置文件出现异常！", e);
        }
        return "AppPay";
    }

    /**
     * 支付确认
     */
    @RequestMapping("/appConfirm")
    public String appConfirm(HttpServletRequest request)
    {
        // 獲取頁面參數封裝成model並生成簽名
        AppPayModel model = new AppPayModel(request);
        // 將簽名放入request對象返回頁面
        request.setAttribute("sign", model.getSign());
        return "AppConfirm";
    }

    /**
     * 订单查询
     */
    @RequestMapping("/query")
    public String query(HttpServletRequest request)
    {
        try
        {
            request.setAttribute("safetyKey", payService.getSafetyKey());
            // 獲取頁面參數封裝成model並生成簽名
            QueryModel model = new QueryModel(request);
            // 將簽名放入request對象返回頁面
            request.setAttribute("sign", model.getSign());
        }
        catch (IOException e)
        {
            LOG.error("读取配置文件出现异常！", e);
        }
        return "Query";
    }

    /**
     * 提交查询
     */
    @RequestMapping("/submitQuery")
    public String submitQuery(HttpServletRequest request)
    {
        try
        {
            payService.submitQuery(request);
        }
        catch (IOException e)
        {
            LOG.error("接口调用出现异常！", e);
        }
        return "Query";
    }

    /**
     * 回调
     */
    @RequestMapping("/callback")
    public String callback(HttpServletRequest request)
    {
        return "Callback";
    }
}