package com.suncity.pay.service;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.Properties;
import java.util.Random;

import javax.servlet.http.HttpServletRequest;

import org.apache.log4j.Logger;

import com.suncity.pay.model.QueryModel;

public class PayService
{
    /**
     * 日志记录对象
     */
    private static final Logger LOG = Logger.getLogger(PayService.class);

    /**
     * 配置文件名称
     */
    private static final String CONFIG_FILE_NAME = "config.properties";

    /**
     * 读取配置文件，并将配置项装入map
     * @param request 请求消息体
     * @throws IOException 可能抛出IOException
     * @author Terry
     */
    public void readConfigFile(Map<String, String> map) throws IOException
    {
        // 读取文件
        LOG.debug("读取配置文件，文件名称：" + CONFIG_FILE_NAME);
        ClassLoader classLoader = Thread.currentThread().getContextClassLoader();
        InputStream inputStream = classLoader.getResourceAsStream(CONFIG_FILE_NAME);

        // 加载属性列表
        Properties prop = new Properties();
        prop.load(inputStream);

        // 迭代获取配置属性
        Iterator<String> it = prop.stringPropertyNames().iterator();
        while (it.hasNext())
        {
            String key = it.next();
            map.put(key, prop.getProperty(key));
        }

        // 生成一个随机的单号（偷懒行为）
        map.put("p4_orderno", genRandomNum(14));

        // 销毁读取流
        inputStream.close();
    }

    /**
     * 获取商户密钥
     * @return 从配置文件读取的商户密钥
     * @author Terry
     * @throws IOException 可能抛出IOException
     */
    public String getSafetyKey() throws IOException
    {
        Map<String, String> map = new HashMap<>();
        readConfigFile(map);
        return map.get("safetyKey");
    }

    /**
     * 提交查询接口
     * @param request 请求消息体
     * @author Terry
     * @throws IOException 可能抛出IOException
     */
    public void submitQuery(HttpServletRequest request) throws IOException
    {
        String url = request.getParameter("txtUrl");
        QueryModel model = new QueryModel(request);
        String msg = sendPost(url, model.getParamString());
        request.setAttribute("msg", msg);
    }

    /**
     * 发送HTTP POST请求
     * @param url 请求url
     * @param param 请求参数
     * @return 接口返回信息
     * @throws IOException 可能抛出IOException
     * @author Terry
     */
    private String sendPost(String url, String param) throws IOException
    {
        OutputStreamWriter out = null;
        BufferedReader in = null;
        String result = "";
        URL realUrl = new URL(url);

        HttpURLConnection conn = (HttpURLConnection) realUrl.openConnection();
        conn.setDoOutput(true);
        conn.setDoInput(true);

        // 设置请求方式
        conn.setRequestMethod("POST");

        // 设置通用的请求属性
        conn.setRequestProperty("accept", "*/*");
        conn.setRequestProperty("connection", "Keep-Alive");
        conn.setRequestProperty("user-agent", "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1;SV1)");
        conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");

        // 建立连接
        conn.connect();

        // 获取URLConnection对象对应的输出流
        out = new OutputStreamWriter(conn.getOutputStream(), "UTF-8");

        // 发送请求参数
        out.write(param);

        // flush输出流的缓冲
        out.flush();

        // 定义BufferedReader输入流来读取URL的响应
        in = new BufferedReader(new InputStreamReader(conn.getInputStream()));
        String line;
        while ((line = in.readLine()) != null)
        {
            result += line;
        }

        // 关闭流
        out.close();
        in.close();

        // 返回结果
        return result;
    }

    /**
     * 随机数字字符串，为了不用每次演示都手动更改订单号
     * @param pwd_len 指定长度
     */
    private String genRandomNum(int pwd_len)
    {
        int i;
        int count = 0;
        char[] str =
        { '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' };
        StringBuffer num = new StringBuffer();
        Random r = new Random();
        while (count < pwd_len)
        {
            // 生成随机数，取绝对值，防止生成负数，
            i = Math.abs(r.nextInt(9));
            if (i >= 0 && i < str.length)
            {
                num.append(str[i]);
                count++;
            }
        }
        return num.toString();
    }
}