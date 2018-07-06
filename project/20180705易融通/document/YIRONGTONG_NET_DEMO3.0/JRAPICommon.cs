using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Text;
using System.Security.Cryptography;
using System.Net;
using System.IO;

namespace JRAPI_NET_DEMO
{
    public class JRAPICommon
    {
        /// <summary>
        /// MD5函数
        /// </summary>
        /// <param name="str">原始字符串</param>
        /// <param name="half">为真则为16位,否则32位</param>
        /// <returns>MD5结果</returns>
        public static string MD5(string str, bool half)
        {
            byte[] b = Encoding.UTF8.GetBytes(str);
            b = new MD5CryptoServiceProvider().ComputeHash(b);
            string ret = "";
            for (int i = 0; i < b.Length; i++)
            {
                ret += b[i].ToString("x").PadLeft(2, '0');
            }
            if (half)
            {
                ret = ret.Substring(8, 16);
            }
            return ret;
        }
        public static string getSubmitForm(Dictionary<string, string> dict, string method, string action)
        {
            StringBuilder sbForm = new StringBuilder();
            sbForm.AppendFormat("<form name=\"myform\" id=\"myform\" method=\"{0}\" action=\"{1}\">", method, action);
            foreach (KeyValuePair<string, string> temp in dict)
            {
                sbForm.AppendFormat("<input type=\"hidden\" name=\"{0}\" value=\"{1}\" />", temp.Key, temp.Value);
            }
            sbForm.Append("</form>");
            return sbForm.ToString();
        }
        /// <summary>
        /// 把数组所有元素，用连接字符拼接成字符串
        /// </summary>
        /// <param name="sArray">需要拼接的数组</param>
        /// <returns>拼接完成以后的字符串</returns>
        public static string CreateLinkString(Dictionary<string, string> dicArray)
        {
            StringBuilder prestr = new StringBuilder();
            foreach (KeyValuePair<string, string> temp in dicArray)
            {
                prestr.Append(temp.Key + "=" + temp.Value + "&");
            }

            //去掉最後一個&字符
            int nLen = prestr.Length;
            prestr.Remove(nLen - 1, 1);

            return prestr.ToString();
        }
        /// <summary>
        /// http POST请求url
        /// </summary>
        /// <param name="apiUrl"></param>
        /// <param name="method_name"></param>
        /// <param name="postData"></param>
        /// <returns></returns>
        public static string GetHttpWebResponse(string url, string postData)
        {
            HttpWebRequest request = (HttpWebRequest)HttpWebRequest.Create(url);
            request.Method = "POST";
            request.ContentType = "application/x-www-form-urlencoded";
            //request.ContentLength = postData.Length;
            request.Timeout = 200000;
            request.Proxy = null;
            HttpWebResponse response = null;

            try
            {
                StreamWriter swRequestWriter = new StreamWriter(request.GetRequestStream());
                swRequestWriter.Write(postData);

                if (swRequestWriter != null)
                    swRequestWriter.Close();

                response = (HttpWebResponse)request.GetResponse();
                using (StreamReader reader = new StreamReader(response.GetResponseStream(), Encoding.UTF8))
                {
                    return reader.ReadToEnd();
                }
            }
            catch (Exception ex)
            {
                return ex.Message;
            }
            finally
            {
                if (response != null)
                    response.Close();
            }
        }
    }
}