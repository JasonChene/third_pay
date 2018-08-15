package com.pay.util;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;

/* *
 *类名：AlipayFunction
 *功能：支付宝接口公用函数类
 *详细：该类是请求、通知返回两个文件所调用的公用函数核心处理文件，不需要修改
 *版本：3.3
 *日期：2012-08-14
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 *该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */

public class payCore {


    /** 
     * 写日志，方便测试（看网站需求，也可以改成把记录存入数据库）
     * @param sWord 要写入日志里的文本内容
     */
    public static void logResult(String sWord) {
        FileWriter writer = null;
        try {
        	File logFile = new File("/usr/local/tomcat7/logs/"+ "pay_log.txt");
			if (!logFile.getParentFile().exists()) {
				logFile.getParentFile().mkdirs();
			}
            writer = new FileWriter(logFile, true);
            writer.append(sWord+ "\n");
        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            if (writer != null) {
                try {
                    writer.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
    }
}
