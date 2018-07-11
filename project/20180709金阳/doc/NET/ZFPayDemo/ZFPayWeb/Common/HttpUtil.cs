using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Web;

namespace ZFPayWeb.Common
{
    public class HttpUtil
    {
        private int _timeout = 100000;

        /// <summary>
        /// 请求与响应的超时时间
        /// </summary>
        public int Timeout
        {
            get { return this._timeout; }
            set { this._timeout = value; }
        }

        static public HttpUtil Create()
        {
            return new HttpUtil();
        }


        public string DoPost(string url, string postdata, Encoding encoding)
        {
            HttpWebRequest req = GetWebRequest(url, "POST");
            byte[] postData = Encoding.UTF8.GetBytes(postdata);
            System.IO.Stream reqStream = req.GetRequestStream();
            reqStream.Write(postData, 0, postData.Length);
            reqStream.Close();
            //TODO:
            string reqInfo = "请求链接：" + url + "?" + postdata;
            Debug.WriteLine(reqInfo);
            Console.WriteLine(reqInfo);

            HttpWebResponse rsp = (HttpWebResponse)req.GetResponse();
            //Encoding encoding = Encoding.GetEncoding(rsp.CharacterSet);

            return GetResponseAsString(rsp, encoding);
        }

        /// <summary>
        /// 执行HTTP GET请求。
        /// </summary>
        /// <param name="url">请求地址</param>
        /// <param name="parameters">请求参数</param>
        /// <returns>HTTP响应</returns>
        public string DoGet(string url, IDictionary<string, string> parameters)
        {
            if (parameters != null && parameters.Count > 0)
            {
                if (url.Contains("?"))
                {
                    url = url + "&" + BuildQuery(parameters);
                }
                else
                {
                    url = url + "?" + BuildQuery(parameters);
                }
            }

            HttpWebRequest req = GetWebRequest(url, "GET");
            req.ContentType = "application/x-www-form-urlencoded;charset=utf-8";

            HttpWebResponse rsp = (HttpWebResponse)req.GetResponse();
            Encoding encoding = Encoding.GetEncoding(rsp.CharacterSet);
            return GetResponseAsString(rsp, encoding);
        }


        private HttpWebRequest GetWebRequest(string url, string method)
        {
            HttpWebRequest req = (HttpWebRequest)WebRequest.Create(url);
            req.ServicePoint.Expect100Continue = false;
            //req.ContentType = "application/json;charset=utf-8";   //"application/json;charset=utf-8"; 
            req.ContentType = "application/x-www-form-urlencoded"; 
            req.Accept = "*/*";
            //req.Headers.Add("Accept-Language", "zh-cn");
            //req.Headers.Add("Accept-Encoding", "gzip, deflate");
            req.Method = method;
            req.KeepAlive = true;
            //req.UserAgent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
            req.UserAgent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; .NET CLR 1.1.4322)";
            req.Timeout = this._timeout;

            //req.AllowAutoRedirect = true;//获取或设置一个值，该值指示请求是否应跟随重定向响应。
            //req.MaximumAutomaticRedirections = 5;
            return req;
        }

        /// <summary>
        /// 把响应流转换为文本。
        /// </summary>
        /// <param name="rsp">响应流对象</param>
        /// <param name="encoding">编码方式</param>
        /// <returns>响应文本</returns>
        private string GetResponseAsString(HttpWebResponse rsp, Encoding encoding)
        {
            System.IO.Stream stream = null;
            StreamReader reader = null;

            try
            {
                // 以字符流的方式读取HTTP响应
                stream = rsp.GetResponseStream();
                reader = new StreamReader(stream, encoding);
                return reader.ReadToEnd();
            }
            finally
            {
                // 释放资源
                if (reader != null) reader.Close();
                if (stream != null) stream.Close();
                if (rsp != null) rsp.Close();
            }
        }

        /// <summary>
        /// 按KEY升序，降序
        /// </summary>
        /// <param name="parameters">IDictionary</param>
        /// <param name="basc">Key,True-升序,False-降序</param>
        /// <returns></returns>
        public static string BuildQuery(IDictionary<string, string> parameters, bool basc)
        {
            //IDictionary<string, string> m_dic;
            if (basc)//升序
            {
                parameters = parameters.OrderBy(o => o.Key).ToDictionary(o => o.Key, p => p.Value);
            }
            else//降序
            {
                parameters = parameters.OrderByDescending(o => o.Key).ToDictionary(o => o.Key, p => p.Value);
            }
            return BuildQuery(parameters);

        }

        /// <summary>
        /// 组装普通文本请求参数。
        /// </summary>
        /// <param name="parameters">Key-Value形式请求参数字典</param>
        /// <returns>URL编码后的请求数据</returns>
        public static string BuildQuery(IDictionary<string, string> parameters)
        {
            StringBuilder m_postData = new StringBuilder();
            bool m_hasParam = false;

            IEnumerator<KeyValuePair<string, string>> m_dem = parameters.GetEnumerator();
            while (m_dem.MoveNext())
            {
                string m_name = m_dem.Current.Key;
                string m_value = m_dem.Current.Value;
                // 忽略参数名或参数值为空的参数
                if (!string.IsNullOrEmpty(m_name) && !string.IsNullOrEmpty(m_value))
                {
                    if (m_hasParam)
                    {
                        m_postData.Append("&");
                    }

                    m_postData.Append(m_name);
                    m_postData.Append("=");
                    //postData.Append(HttpUtility.UrlEncode(value, Encoding.UTF8));
                    m_postData.Append(m_value);
                    m_hasParam = true;
                }
            }

            return m_postData.ToString();
        }
    }
}