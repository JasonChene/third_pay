package com.yipin.pay.servlet;


import com.yipin.pay.PayDemo;
import org.apache.commons.codec.digest.DigestUtils;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.io.PrintWriter;

public class PayNotifyServlet extends javax.servlet.http.HttpServlet {
    @Override
    protected void doPost(HttpServletRequest request,
                          HttpServletResponse response) throws IOException {
        System.out.println("----------------------------------");
        String success = request.getParameter("success");
        String orderNumber = request.getParameter("orderNumber");
        String money = request.getParameter("money");
        String payDate = request.getParameter("payDate");
        String key = PayDemo.key;
        String signatureReturn = request.getParameter("signature");
        String laws = success + "&" + orderNumber + "&" + money + "&" + payDate + "&" + key;
        String signature = DigestUtils.md5Hex(laws).toUpperCase();
        PrintWriter writer = response.getWriter();
        if (signature.equals(signatureReturn)) {
            writer.print("SUCCESS");
            System.out.println("签名校验成功！！！");
        } else {
            writer.print("Failed");
            System.out.println("签名校验失败！！！");
        }
        System.out.println("----------------------------------");
        writer.flush();
        writer.close();
    }

    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws IOException {
        this.doPost(request, response);
    }
}
