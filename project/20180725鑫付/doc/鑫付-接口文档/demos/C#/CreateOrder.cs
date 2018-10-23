using System;
using System.Collections.Generic;
using System.Configuration;
using System.Data;
using System.Data.SqlClient;
using System.Drawing;
using System.IO;
using System.Linq;
using System.Text;
using System.Web;
using WeixinApplication.Models;
namespace WeixinApplication.demo
{

    public class interfaceReturnDataModel
    {
        public string respcd;
        public string respmsg;
        public interfaceReturnData data;
    }
    public class interfaceReturnData
    {
        public string src_code;
        public string sign;
        public string trade_no;
        public string out_trade_no;
        public string total_fee;
        public string time_start;
        public string pay_time;
        public string trade_type;
        public string goods_name;
        public string fee_type;
        public string order_status;
        public string pay_params;
    }
    public class BaseClass
    {
        public void CreateOrder()
        {
            string notifyUrl = ConfigurationManager.AppSettings["notifyurl"].ToString();//回 调地址 
            string mchid = ConfigurationManager.AppSettings["merno"].ToString();//商户号
            string key = ConfigurationManager.AppSettings["key"].ToString();//商户密钥
            string srcCode = ConfigurationManager.AppSettings["srcCode"].ToString();//商户唯一标识
            Dictionary<string, string> dic = new Dictionary<string, string>();
            dic.Add("src_code", srcCode);
            dic.Add("mchid", mchid);
            dic.Add("out_trade_no", DateTime.Now.ToString("yyyyMMddhhmmss"));
            dic.Add("total_fee","100");
            dic.Add("time_start", DateTime.Now.ToString("yyyyMMddhhmmss"));
            dic.Add("goods_name", "充值");
            dic.Add("trade_type", "60104");
            dic.Add("finish_url", notifyUrl);

            string tempstr = ConvertDic2Urlparam(dic) + "&key=" + key;//转换为URLPARAM串
            string sign = AlipayMD5.getmd5(tempstr).ToUpper();//MD5加密
            dic.Add("sign", sign);



            string url = ConfigurationManager.AppSettings["tradeurl"].ToString()+ "/trade/pay";//API地址
            string pdata = ConvertDic2Urlparam(dic);//转换为URLPARAM串

            
            string r = PostRequest(url, pdata, Encoding.UTF8, Encoding.UTF8);
            

            interfaceReturnDataModel rdic = Newtonsoft.Json.JsonConvert.DeserializeObject<interfaceReturnDataModel>(r);

            if (rdic.respcd == "0000" && CheckSign(Newtonsoft.Json.JsonConvert.DeserializeObject<Dictionary<string,string>>(Newtonsoft.Json.JsonConvert.SerializeObject(rdic.data)), key))
            {
               //成功
            }
            else {
                //失败
            }
            //db.ExecSql("insert tb_paydata (payid,paydata,datatype) values(" + stringConstrunctor.GetQuotedStr(orderid) + "," + stringConstrunctor.GetQuotedStr(Newtonsoft.Json.JsonConvert.SerializeObject(rd)) + ",1)");

        }
        public  string PostRequest(string url, string postData, Encoding requestCoding, Encoding responseCoding)
        {
            string postUrl = url;
            try
            {
                if (url.StartsWith("https", StringComparison.OrdinalIgnoreCase))
                {
                    ServicePointManager.ServerCertificateValidationCallback = new RemoteCertificateValidationCallback(CheckValidationResult);
                }
                byte[] byteArray = requestCoding.GetBytes(postData);
                HttpWebRequest webRequest = (HttpWebRequest)WebRequest.Create(new Uri(postUrl));
                webRequest.Method = "POST";
                webRequest.ContentType = "application/x-www-form-urlencoded;charset=UTF-8";
                webRequest.ContentLength = byteArray.Length;
                Stream newStream = webRequest.GetRequestStream();
                newStream.Write(byteArray, 0, byteArray.Length);
                newStream.Close();
                //接收返回信息：
                HttpWebResponse response = (HttpWebResponse)webRequest.GetResponse();
                StreamReader php = new StreamReader(response.GetResponseStream(), responseCoding);
                string result = php.ReadToEnd();
                return result;
            }
            catch (Exception ex)
            {
                return ex.Message;
            }
        }
        /// <summary>
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
                if (item.Value != null && item.Value.Trim().Length > 0 && item.Key != "sign")
                {
                    sigSource = sigSource + item.Key + "=" + item.Value + "&";
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