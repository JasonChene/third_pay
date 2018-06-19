using System;
using System.Collections.Generic;
using System.Data;
using System.IO;
using System.Text;
using System.Web;
using System.Configuration;


namespace Api.demo
{
    public partial class payforsuccess : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (Request.RequestType.ToUpper() == "POST")
            {
                Response.Write("SUCCESS");

                string inputData = ReadStream(Request.InputStream);
                string merno = ConfigurationManager.AppSettings["merno"].ToString(); //商户号
                string key = ConfigurationManager.AppSettings["key"].ToString(); //密钥

                Dictionary<string, string> dic111 = ConvertUrlparam2Dic(inputData);
                bool b =dic111["order_status"] == "3" && CheckSign(dic111, key);
                string out_trade_no = dic111["out_trade_no"];

                if (b)//验签通过且已经支付
                {

                }
            }
        }
        /// <summary>
        /// 将URL参数串转换成为字典
        /// </summary>
        /// <param name="resultMsg"></param>
        /// <returns></returns>
        private Dictionary<string, string> ConvertUrlparam2Dic(string resultMsg)
        {
            string[] results = resultMsg.Split('&');
            string[] temp;
            Dictionary<string, string> resultItem = new Dictionary<string, string>();
            foreach (string item in results)
            {
                temp = item.Split('=');
                if (temp.Length == 2)
                {
                    resultItem.Add(temp[0], temp[1]);
                }
                else if (temp.Length > 2)
                {
                    string tmp = "";
                    for (int i = 1; i < temp.Length; i++)
                    {
                        tmp += (tmp == "" ? "" : "=") + temp[i];
                    }
                    resultItem.Add(temp[0], tmp);
                }
                else
                {
                    resultItem.Add(temp[0], "");
                }

            }
            return resultItem;
        }
        private static string ReadStream(Stream stream)
        {
            using (var reader = new StreamReader(stream, Encoding.UTF8))
            {
                return reader.ReadToEnd();
            }
        }/// <summary>
         /// 验证签名
         /// </summary>
         /// <param name="dic"></param>
         /// <param name="key"></param>
         /// <returns></returns>
        private bool CheckSign(Dictionary<string, string> dic, string key)
        {
            dic = SortDictionary(dic);
            string sigSource = "";
            foreach (var item in dic)
            {
                if (item.Value != null && item.Value.Trim().Length > 0 && item.Key != "sign_type" && item.Key != "sign")
                {
                    sigSource = sigSource + item.Key + "=" + HttpUtility.UrlDecode(item.Value) + "&";
                }
            }
            if (sigSource.Length > 1)
            {
                sigSource = sigSource.Substring(0, sigSource.Length - 1);
            }
            sigSource += "&key=" + key;
            return AlipayMD5.getmd5(sigSource).ToUpper() == dic["sign"];
        }
        /// <summary>
        /// 将字典转换成a=1&b=2&c=3字符串
        /// </summary>
        /// <param name="dic"></param>
        /// <returns></returns>
        private string ConvertDic2Urlparam(Dictionary<string, string> dic)
        {
            dic = SortDictionary(dic);
            string sigSource = "";
            foreach (var item in dic)
            {
                if (item.Value != null && item.Value.Trim().Length > 0)
                {
                    sigSource = sigSource + item.Key + "=" + item.Value + "&";
                }
            }
            if (sigSource.Length > 1)
            {
                sigSource = sigSource.Substring(0, sigSource.Length - 1);
            }
            return sigSource;
        }
        /// <summary>
        /// 将数据字典按ASCLL码顺序排序
        /// </summary>
        /// <param name="dic"></param>
        /// <returns></returns>
        private Dictionary<string, string> SortDictionary(Dictionary<string, string> dic)
        {
            List<KeyValuePair<string, string>> myList = new List<KeyValuePair<string, string>>(dic);
            myList.Sort(delegate (KeyValuePair<string, string> s1, KeyValuePair<string, string> s2)
            {
                return s1.Key.CompareTo(s2.Key);
            });
            dic.Clear();
            foreach (KeyValuePair<string, string> pair in myList)
            {
                dic.Add(pair.Key, pair.Value);
            }
            return dic;
        }
    }
}