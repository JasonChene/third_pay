package demo;

import java.io.File;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.apache.commons.lang3.StringUtils;
import org.apache.http.HttpEntity;
import org.apache.http.NameValuePair;
import org.apache.http.client.config.RequestConfig;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.CloseableHttpResponse;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.ContentType;
import org.apache.http.entity.mime.HttpMultipartMode;
import org.apache.http.entity.mime.MultipartEntityBuilder;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.entity.mime.content.StringBody;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class BaseDemo {

    public static Logger logger = LoggerFactory.getLogger(BaseDemo.class);

    
    protected String reqApi(String url, Map<String, String> reqMap) {
        String html = StringUtils.EMPTY;

        List<NameValuePair> params = new ArrayList<NameValuePair>();
        Iterator<Map.Entry<String, String>> entries = reqMap.entrySet().iterator();
        while (entries.hasNext()) {
            Map.Entry<String, String> entry = entries.next();
            params.add(new BasicNameValuePair(entry.getKey(), entry.getValue()));
        }

        CloseableHttpClient httpClient = HttpClients.createDefault();
        HttpPost post = new HttpPost(url);

        int connectionRequestTimeout = 20000;
        int connectTimeout = 20000;
        int socketTimeout = 20000;
        RequestConfig config = RequestConfig.custom().setConnectionRequestTimeout(connectionRequestTimeout).setConnectTimeout(connectTimeout).setSocketTimeout(socketTimeout)
                .build();
        logger.debug("request socketTimeout:{}, connectTimeout:{}, socketTimeout:{}",
                new String[] { String.valueOf(connectionRequestTimeout), String.valueOf(connectTimeout), String.valueOf(socketTimeout) });

        CloseableHttpResponse httpResponse = null;
        try {
            post.setEntity(new UrlEncodedFormEntity(params, "utf-8"));
            post.setConfig(config);
            httpResponse = httpClient.execute(post);
            HttpEntity httpEntity = httpResponse.getEntity();
            if (httpResponse.getStatusLine().getStatusCode() == 200) {
                html = EntityUtils.toString(httpEntity);
                System.out.println(html);
            }
            EntityUtils.consume(httpEntity);
        } catch (Exception e) {
            logger.error(e.getMessage(), e);
        } finally {
            if (httpResponse != null) {
                try {
                    httpResponse.close();
                } catch (Exception e) {
                    logger.error(e.getMessage(), e);
                }
            }
        }
        return html;
    }

    protected String reqFileApi(String url, Map<String, String> reqMap, File file) {
        String html = StringUtils.EMPTY;

        MultipartEntityBuilder builder = MultipartEntityBuilder.create();
        builder.setMode(HttpMultipartMode.BROWSER_COMPATIBLE);

        Iterator<Map.Entry<String, String>> entries = reqMap.entrySet().iterator();
        while (entries.hasNext()) {
            Map.Entry<String, String> entry = entries.next();
            builder.addPart(entry.getKey(), new StringBody(entry.getValue(), ContentType.MULTIPART_FORM_DATA));
        }
        FileBody fileBody = new FileBody(file, ContentType.DEFAULT_BINARY);
        builder.addPart("file", fileBody);

        CloseableHttpClient httpClient = HttpClients.createDefault();
        HttpPost post = new HttpPost(url);

        int connectionRequestTimeout = 20000;
        int connectTimeout = 20000;
        int socketTimeout = 20000;
        RequestConfig config = RequestConfig.custom().setConnectionRequestTimeout(connectionRequestTimeout).setConnectTimeout(connectTimeout).setSocketTimeout(socketTimeout)
                .build();
        logger.debug("request socketTimeout:{}, connectTimeout:{}, socketTimeout:{}",
                new String[] { String.valueOf(connectionRequestTimeout), String.valueOf(connectTimeout), String.valueOf(socketTimeout) });

        CloseableHttpResponse httpResponse = null;
        try {
            post.setEntity(builder.build());
            post.setConfig(config);
            httpResponse = httpClient.execute(post);
            HttpEntity httpEntity = httpResponse.getEntity();
            if (httpResponse.getStatusLine().getStatusCode() == 200) {
                html = EntityUtils.toString(httpEntity);
                System.out.println(html);
            }
            EntityUtils.consume(httpEntity);
        } catch (Exception e) {
            logger.error(e.getMessage(), e);
        } finally {
            if (httpResponse != null) {
                try {
                    httpResponse.close();
                } catch (Exception e) {
                    logger.error(e.getMessage(), e);
                }
            }
        }
        return html;
    }

}
