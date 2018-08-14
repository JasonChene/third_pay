﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using Demo.Class;
namespace merchant_demo
{
    public partial class return1 : System.Web.UI.Page
    {

        //具体含义  查看文档  
        public static string merchantCode = "1000000001";
        public static string md5key = "123456ADSEF";
        public static string orderId = "";
        public static string outOrderId = "";
        public static string amount = "1";
        public static string result = "";
        //签名数据 必须这种格式 按照a-z排序
        public static string signValue = "amount=" + amount + "&merchantCode=" + merchantCode + "&orderId=" + orderId +"&outOrderId="+outOrderId+ "&KEY=" + md5key;
        public static string sign = MD5.md5Sign(signValue);
        protected void Page_Load(object sender, EventArgs e)
        {
            sendPostKeyValue();
        }
        public static string sendPostKeyValue()
        {
            System.Net.WebClient WebClientObj = new System.Net.WebClient();
            System.Collections.Specialized.NameValueCollection PostVars = new System.Collections.Specialized.NameValueCollection();
            PostVars.Add("merchantCode", merchantCode);
            PostVars.Add("orderId", orderId);
            PostVars.Add("outOrderId", outOrderId);
            PostVars.Add("amount", amount);
            PostVars.Add("sign", sign);
            try
            {
                byte[] byRemoteInfo = WebClientObj.UploadValues("", "POST", PostVars);
                string sRemoteInfo = System.Text.Encoding.Default.GetString(byRemoteInfo);
                result += sRemoteInfo;
                Console.Write("resultS：" + result);

            }
            catch
            {
                return result;
            }
            return result;

        } 
    }
}