using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Data;
using System.Configuration;
using System.Web.Security;
using System.Web.UI.HtmlControls;
using System.Security.Cryptography;
using System.Text;

namespace demo_payment_zs_cs
{
    public partial class paymentQueryState : System.Web.UI.Page
    {
        static string merchantCode = "1000000001";
        static string outOrderId = "2222123222222";//必须每次都不同
        static string nonceStr = "123456786464";
        static string md5key = "123456ADSEF";
        static string url = "";



        protected void Page_Load(object sender, EventArgs e)
        {
            //组建签名原文
            string signSrc =  "merchantCode=" + merchantCode + "&nonceStr=" + nonceStr + "&outOrderId=" + outOrderId + "&KEY=" + md5key;
            //签名
            string sign = GetMD5(signSrc);
            string response = sendPost(url, sign);
            Response.Write("应答：" + response);
        }


        //获取md5签名值：
        public static string GetMD5(string QueryStr)
        {
            try
            {
                MD5CryptoServiceProvider MD5 = new MD5CryptoServiceProvider(); //32位MD5
                byte[] by = UTF8Encoding.UTF8.GetBytes(QueryStr);
                string resultPass = System.Text.UTF8Encoding.Unicode.GetString(by);
                byte[] output = MD5.ComputeHash(by);
                string rs = BitConverter.ToString(output);
                string nn = rs.Replace("-", "");
                return nn;
            }
            catch (Exception e)
            {
                throw new Exception(e.Message);
            }
        }
        public static string sendPost(string url, string sign)
        {
            //发送数据
            System.Net.WebClient WebClientObj = new System.Net.WebClient();
            System.Collections.Specialized.NameValueCollection PostVars = new System.Collections.Specialized.NameValueCollection();
            PostVars.Add("merchantCode", merchantCode);
            PostVars.Add("outOrderId", outOrderId);          
            PostVars.Add("nonceStr", nonceStr);           
            PostVars.Add("sign", sign);
            try
            {
                byte[] byRemoteInfo = WebClientObj.UploadValues(url, "POST", PostVars);
                string sRemoteInfo = System.Text.Encoding.Default.GetString(byRemoteInfo);
                //这是获取返回信息
                return sRemoteInfo;
            }
            catch
            { }
            return "no response";
        }
    }
}