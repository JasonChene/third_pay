using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Web.Http;
using BJTMallApi.Models;
using System.Web.Http.Cors;
using System.Data;
using System.Web.Script.Serialization;
using Api.iapppay_tx;
using System.Text;
using System.Configuration;

namespace Api.Controllers
{
    public class tx
    {

        /// <param name="reqdata"></param>
        /// <param name="zh">支行</param>
        /// <returns></returns>
        public void tx(reqData reqdata, string zh)
        {
            lock (locker)
            {

                string url = ConfigurationManager.AppSettings["txurl"].ToString();//提现API地址
                string mchid = ConfigurationManager.AppSettings["merno"].ToString();//商户号
                string key = ConfigurationManager.AppSettings["key"].ToString();//商户密钥
                string srcCode = ConfigurationManager.AppSettings["srcCode"].ToString();//商户唯一标识
                Dictionary<string, string> dic = new Dictionary<string, string>();

                dic.Add("out_sn", "123123123");//订单 号
                dic.Add("account_name", "张三");
                dic.Add("bank_type", "对私");
                dic.Add("card_type", "储蓄卡");
                dic.Add("account_no", "621700003132314");
                dic.Add("amt", "100");
                dic.Add("head_bank_name", "中国建设银行");
                dic.Add("noncestr", Guid.NewGuid().ToString("N").Substring(0, 16));//随机串
                string tempstr = ConvertDic2Urlparam(dic) + "&key=" + key;
                string sign = getmd5(tempstr).ToUpper();//MD5加密

                tempstr = ConvertDic2Urlparam(dic) + "&sign=" + sign;
                string pubkey = Com.demo.RSACryptoService.GetPublicKey(HttpContext.Current.Server.MapPath("~/keys") + "\\public_key.pem");
                string prvkey = Com.demo.RSACryptoService.GetPublicKey(HttpContext.Current.Server.MapPath("~/keys") + "\\rsa_private_key.pem");
                Com.demo.RSACryptoService rsa = new Com.demo.RSACryptoService(null, pubkey);
                string rsasign = rsa.Encrypt(tempstr);
                string postdata = "encrypt_data=" + HttpUtility.UrlEncode(rsasign) + "&src_code=" + srcCode;
               
                string rr = HttpHelper.PostRequest(url, postdata, System.Text.Encoding.UTF8, System.Text.Encoding.UTF8);
                Dictionary<string, object> rdic = Newtonsoft.Json.JsonConvert.DeserializeObject<Dictionary<string, object>>(rr);
               
                if (rdic["respcd"].ToString() == "0000")
                {
                    rsa = new Com.demo.RSACryptoService(prvkey, null);
                    string data_ = rsa.Decrypt(rdic["data"].ToString());
                    Dictionary<string, string> ddic = ConvertUrlparam2Dic(data_);
                    if (ddic["status"] != "3")
                    {
                        //提现成功
                    }
                    else {
                        //提现失败
                    }
                }
                else
                {
                    //失败
                }
            }
        }
        public  string getmd5(string s)
        {
            return System.Web.Security.FormsAuthentication.HashPasswordForStoringInConfigFile(s, "MD5");
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
        #endregion
    }

}