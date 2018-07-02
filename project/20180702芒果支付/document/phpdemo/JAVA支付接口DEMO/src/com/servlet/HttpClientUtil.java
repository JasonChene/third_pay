package com.servlet;

import java.io.BufferedReader;
import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.io.UnsupportedEncodingException;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import java.util.Map;
import java.util.Set;

import org.apache.commons.httpclient.DefaultHttpMethodRetryHandler;
import org.apache.commons.httpclient.Header;
import org.apache.commons.httpclient.HttpClient;
import org.apache.commons.httpclient.HttpException;
import org.apache.commons.httpclient.HttpStatus;
import org.apache.commons.httpclient.methods.GetMethod;
import org.apache.commons.httpclient.methods.PostMethod;
import org.apache.commons.httpclient.params.HttpMethodParams;

 
public class HttpClientUtil {

	public int count = 0;
	
	public String httpGet(String url, String charSet, int connTimeOut, int soTimeOut) {

		HttpClient httpClient = new HttpClient();

		GetMethod getMethod = new GetMethod(url);

		if (connTimeOut != 0)
			httpClient.getHttpConnectionManager().getParams().setConnectionTimeout(connTimeOut);
		if (soTimeOut != 0)
			httpClient.getHttpConnectionManager().getParams().setSoTimeout(soTimeOut);

		charSet = null == charSet ? "GBK" : charSet;
		getMethod.getParams().setParameter(HttpMethodParams.HTTP_CONTENT_CHARSET, charSet);

		getMethod.getParams().setParameter(HttpMethodParams.RETRY_HANDLER, new DefaultHttpMethodRetryHandler());
		try {
			int statusCode = httpClient.executeMethod(getMethod);
			if (statusCode == HttpStatus.SC_OK) {
				byte[] responseBody = getMethod.getResponseBody();

				return new String(responseBody, charSet);
			}
			return "FAIL";
		} catch (HttpException e) {
			e.printStackTrace();
			return "EXCEPTION";
		} catch (IOException e) {
			e.printStackTrace();
			return "EXCEPTION";
		} finally {
			getMethod.releaseConnection();
		}
	}

	public String httpGet(String url, String charSet, String connTimeOutStr, String soTimeOutStr) {

		int connTimeOut = 0;
		int soTimeOut = 0;
		if (null != connTimeOutStr && (!"".equals(connTimeOutStr))) {
			connTimeOut = Integer.parseInt(connTimeOutStr);
		}
		if (null != soTimeOutStr && (!"".equals(soTimeOutStr))) {
			soTimeOut = Integer.parseInt(soTimeOutStr);
		}
		return httpGet(url, charSet, connTimeOut, soTimeOut);
	}
	
	public String httpPost(String url, Map<String, String> nameValue, String charSet, int connTimeOut, int soTimeOut) {

		HttpClient httpClient = new HttpClient();

		PostMethod postMethod = new PostMethod(url);
		if (connTimeOut != 0)
			httpClient.getHttpConnectionManager().getParams().setConnectionTimeout(connTimeOut);
		if (soTimeOut != 0)
			httpClient.getHttpConnectionManager().getParams().setSoTimeout(soTimeOut);

		if (null != nameValue) {
			Set<String> keys = nameValue.keySet();
			for (String key : keys) {
				postMethod.setParameter(key, nameValue.get(key));
			}
		}

		charSet = null == charSet ? "GBK" : charSet;
		postMethod.getParams().setParameter(HttpMethodParams.HTTP_CONTENT_CHARSET, charSet);

		try {
			int statusCode = httpClient.executeMethod(postMethod);

			if (statusCode == HttpStatus.SC_OK) {
				byte[] responseBody = postMethod.getResponseBody();
				charSet = null != charSet ? charSet : "GBK";
				return new String(responseBody, charSet);
			} else if (statusCode == HttpStatus.SC_MOVED_PERMANENTLY || statusCode == HttpStatus.SC_MOVED_TEMPORARILY) {

				
				Header locationHeader = postMethod.getResponseHeader("location");
				if (locationHeader != null) {
					String location = locationHeader.getValue();
					if (count == 0) {
						count++;
						return httpPost(location, nameValue, charSet, connTimeOut, soTimeOut);
					} else
						return "FAIL";
				} else {
					return "FAIL";
				}
			} else {
				return "FAIL";
			}
		} catch (HttpException e) {
			e.printStackTrace();
			return "EXCEPTION";
		} catch (IOException e) {
			e.printStackTrace();
			return "EXCEPTION";
		} finally {
			postMethod.releaseConnection();
		}
	}


	public String httpPost(String url, Map<String, String> nameValue, String charSet, String connTimeOutStr, String soTimeOutStr) {

		int connTimeOut = 0;
		int soTimeOut = 0;
		if (null != connTimeOutStr && (!"".equals(connTimeOutStr))) {
			connTimeOut = Integer.parseInt(connTimeOutStr);
		}
		if (null != soTimeOutStr && (!"".equals(soTimeOutStr))) {
			soTimeOut = Integer.parseInt(soTimeOutStr);
		}
		return httpPost(url, nameValue, charSet, connTimeOut, soTimeOut);
	}

	public static String submitPost(String url, String paramContent,String charSet, int connTimeOut,int soTimeOut) {
		StringBuffer message = null;
		java.net.URLConnection connection = null;
		java.net.URL reqUrl = null;
		OutputStreamWriter reqOut = null;
		InputStream in = null;
		BufferedReader br = null;
		String param = paramContent;
		// String identifier = System.currentTimeMillis() + "";
		if(null==charSet||"".equals(charSet)){
			charSet="GBK";
		}
		try {
			message = new StringBuffer();//�������������Ӧ��Ϣ
			reqUrl = new java.net.URL(url);
			connection = reqUrl.openConnection();//��������
			connection.setReadTimeout(soTimeOut);//���ö�ȡ���ݳ�ʱʱ��
			connection.setConnectTimeout(connTimeOut);//�������ӵ�ַ��ʱʱ��
			connection.setDoOutput(true);//ʹ��post��ʽ��ʱ����Ҫʹ�� URL ���ӽ�������������趨Ϊtrue
			reqOut = new OutputStreamWriter(connection.getOutputStream(),charSet);
			reqOut.write(param);//��������Ϣ��ӵ��������
			reqOut.flush();//ˢ�¸����Ļ���
			int charCount = -1;
			in = connection.getInputStream();
			br = new BufferedReader(new InputStreamReader(in, charSet));//�趨��ȡ��Ӧ�ַ��ı����ʽ
			while ((charCount = br.read()) != -1) {
				message.append((char) charCount);//����Ӧ��Ϣ��ӵ�message������
			}
		} catch (Exception ex) {
			ex.printStackTrace();
			return "EXCEPTION";
		} finally {
			try {
				//�رմ򿪵��������������
				in.close();
				reqOut.close();
			} catch (Exception e) {
				e.printStackTrace();
			}
		}
		return message.toString();
	}
	
	/**
	* @Title: submitPost 
	* @Description: java.netʵ�� POST�����ύ
	* @param @param url String
	* @param @param paramContent String ƴװ�õĲ�����
	* @param @param charSet string
	* @param @param connTimeOut int
	* @param @param SoTimeOut int
	* @param @return    �趨�ļ� 
	* @return String    �������� 
	* @throws
	 */
	
	public static String submitPost(String url, String paramContent,String charSet, String connTimeOutStr,String soTimeOutStr) {
		int connTimeOut = 0;
		int soTimeOut = 0;
		if (null != connTimeOutStr && (!"".equals(connTimeOutStr))) {
			connTimeOut = Integer.parseInt(connTimeOutStr);
		}
		if (null != soTimeOutStr && (!"".equals(soTimeOutStr))) {
			soTimeOut = Integer.parseInt(soTimeOutStr);
		}
		return submitPost(url,paramContent,charSet,connTimeOut,soTimeOut);
	}
	/**
	 * 
	 * @param url
	 * @param requestData
	 * @param method
	 * @param charset
	 * @return
	 */
	 public static String submitRequest(String url,String requestData,String method,String charset){
	    	String req_msg  =   requestData;
			
			StringBuffer buffer = new StringBuffer();
			try {
//				String requestUrl = url;
//				log.info("URL-->>" + requestUrl);
				URL requestUrl = new URL(url);
				URLConnection connection = requestUrl.openConnection();
				HttpURLConnection conn = (HttpURLConnection) connection;
				conn.setDoOutput(true);
				conn.setDoInput(true);
				conn.setUseCaches(false);
				conn.setRequestProperty("Content-Type",
						"application/x-www-form-urlencoded;charset="+charset);
				conn.setRequestMethod(method);
				DataOutputStream out = new DataOutputStream(conn.getOutputStream());
				out.writeBytes(req_msg);
				
				DataInputStream inputStream = new DataInputStream(conn.getInputStream());
				BufferedReader bufferedReader = new BufferedReader(
						new InputStreamReader(inputStream, charset));

				String str = null;
				while ((str = bufferedReader.readLine()) != null) {
					buffer.append(str);
				}
//				log.info("HTTP����-->>" + buffer.toString());
				bufferedReader.close();
				inputStream.close();
				inputStream = null;
				conn.disconnect();
			} catch (MalformedURLException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			}
			return buffer.toString();
	    	
	    }
	 /**
	     * ����������ת���ַ���
	     * @param is
	     * @param charset
	     * @return
	     */
	    	public static String InputStreamToString(InputStream is,String charset){
	            BufferedReader reader = null;
	            StringBuffer responseText = null;
	            String readerText = null;
	            try {
	                reader = new BufferedReader(new InputStreamReader(is, charset));
	                responseText = new StringBuffer();
	                readerText = reader.readLine();
	                while(readerText != null){
	                    responseText.append(readerText);
	                    responseText.append(System.getProperty("line.separator"));
	                    readerText = reader.readLine();
	                }
	            } catch (UnsupportedEncodingException e) {
	                e.printStackTrace();
	            } catch (IOException e) {
	                e.printStackTrace();
	            }
	            return responseText.toString();
	        }
}
