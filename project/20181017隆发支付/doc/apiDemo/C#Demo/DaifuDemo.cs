using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using LitJson;

namespace testconsole
{
    public class DaifuDemo
    {
        private string userId = "LFP201808250000";//商户号
		private string version = "V3.6.0.0";

        string[,] bankcodeArray = new string[,]{
            {"BOC","中国银行","BOC"},
            {"ABC","农业银行","ABC"},
            {"ICBC","工商银行","ICBC"},
            {"CCB","建设银行","CCB"},
            {"BCM","交通银行","BOCO"},
            {"CMB","招商银行","CMB"},
            {"CEB","光大银行","CEB"},
            {"CMBC","民生银行","CMBC"}, 
            {"HXB","华夏银行","HXB"},
            {"CIB","兴业银行","CIB"},
            {"CNCB","中信银行","CTTIC"},
            {"SPDB","上海浦东发展银行","SPDB"},
            {"PSBC","中国邮政","PSBS"}
        };
        /// <summary>
        /// 代付
        /// </summary>
        /// <returns></returns>
        public string DFSubmit()
        {
            try
            {
                string Entrustid = "自己代付出款对应id";
                string m_bankcode = "";
                string EntrusBankName = "自己项目对应银行编号";
                for (var i = 0; i < bankcodeArray.GetLength(0); i++)
                {
                    if (EntrusBankName.Contains(bankcodeArray[i, 1]))
                    {
                        m_bankcode = bankcodeArray[i, 0];
                        break;
                    }
                }

                if (m_bankcode == "")
                {
                    return "银行不支持";
                }

                Dictionary<String, String> paramdic = new Dictionary<String, String>();
                paramdic.Add("merchNo", userId);
                paramdic.Add("orderNo", DateTime.Now.ToString("yyyyMMdd_") + Entrustid);
                paramdic.Add("amount", "100");
                paramdic.Add("bankCode", m_bankcode);
                paramdic.Add("bankAccountName", "张三");//开户名
                paramdic.Add("bankAccountNo", "00000000000000");//银行卡号
                paramdic.Add("notifyUrl", "http://127.0.0.1/callBackUrl");// 回调地址
                //排序
                paramdic = paramdic.OrderBy(o => o.Key).ToDictionary(o => o.Key, pp => pp.Value);

                string sourceStr = "{";
                foreach (KeyValuePair<string, string> v in paramdic)
                {
                    sourceStr += string.Format("\"{0}\":\"{1}\",", v.Key, v.Value);
                }
                //{"key":"value"}md5key
                sourceStr = sourceStr.Substring(0, sourceStr.Length - 1) + "}" + "MD5Key";

                paramdic.Add("sign", MD5Encrypt.MD5(sourceStr, false).ToUpper());

                paramdic = paramdic.OrderBy(o => o.Key).ToDictionary(o => o.Key, pp => pp.Value);
                sourceStr = "{";
                foreach (KeyValuePair<string, string> v in paramdic)
                {
                    sourceStr += string.Format("\"{0}\":\"{1}\",", v.Key, v.Value);
                }
                //{"key":"value"..."sign":""}
                sourceStr = sourceStr.Substring(0, sourceStr.Length - 1) + "}";
                //Log.WriteTextLog("RuiFuDaiFuInfo", "代付提交md5加密后：" + sourceStr, DateTime.Now);
                string publicKey = "加密私钥";// base.GateUserEmail;
                string cipher_data = "";
                publicKey = RSAEncodHelper.RSAPublicKeyJava2DotNet(publicKey);

                byte[] cdatabyte = RSAEncodHelper.RSAPublicKeySignByte(sourceStr, publicKey);
                cipher_data = Convert.ToBase64String(cdatabyte);

                string paramstr = "data=" + System.Web.HttpUtility.UrlEncode(cipher_data) + "&merchNo=" + userId + "&version=" + version;
                //Log.WriteTextLog("RuiFuDaiFuInfo", "请求参数：" + paramstr, DateTime.Now);
                //string p = HttpUtil.BuildQuery(paramdic, true);
                string d = paramstr;
                string strResult =HttpPost.SendPost("http://47.94.6.240:9003/api/remit", paramstr, 5000);


                return strResult;
            }
            catch (Exception ex)
            {
                //Log.WriteTextLog("RuiFuDaiFuInfo", "DFSubmit()==>" + ex.Message, DateTime.Now);
                return ex.Message;
            }
        }

        /// <summary>
        /// 代付返回
        /// </summary>
        /// <param name="strResult"></param>
        /// <returns></returns>
        public string DFCallBackHandle(string strResult)
        {
            try
            {
                /*
				{"amount":"10000","merchNo":"XF201806040000","msg":"提交成功","orderNo":"201808011041370902x0S","sign":"289B38555EB6A987A320DB4BC12F644E","stateCode":"00"}                    */
                //Log.WriteTextLog("RuiFuDaiFuInfo", "DFCallBackHandle()返回==>" + strResult, DateTime.Now);
                if (string.IsNullOrEmpty(strResult))
                    return "";
                if (strResult == "银行不支持") return "";
                Dictionary<string, string> paramsDic = new Dictionary<string, string>();
                LitJson.JsonData jd = JsonMapper.ToObject(strResult);
                if (jd == null)
                    return "";
                if ((string)jd["stateCode"] != "00")
                {
                    return string.Format("错误,响应码:{0},响应消息:{1}", (string)jd["stateCode"], (string)jd["msg"]);
                }
                paramsDic.Add("merchNo", (string)jd["merchNo"]);
                paramsDic.Add("stateCode", (string)jd["stateCode"]);
                paramsDic.Add("msg", (string)jd["msg"]);
                paramsDic.Add("orderNo", (string)jd["orderNo"]);
                paramsDic.Add("amount", (string)jd["amount"]);

                paramsDic = paramsDic.OrderBy(o => o.Key).ToDictionary(o => o.Key, pp => pp.Value);
                string sourceStr = "{";
                foreach (KeyValuePair<string, string> v in paramsDic)
                {
                    sourceStr += string.Format("\"{0}\":\"{1}\",", v.Key, v.Value);
                }
                sourceStr = sourceStr.Substring(0, sourceStr.Length - 1) + "}" + userId;
                if (MD5Encrypt.MD5(sourceStr, false).ToUpper().Equals((string)jd["sign"]) == false)
                {
                    return "验签失败!";
                }
                string result = string.Format("金额:{0},状态:{1},消息:{2}", (Convert.ToDecimal((string)jd["amount"])) / 100, (string)jd["stateCode"], (string)jd["msg"]);
                return result;
            }
            catch (Exception ex)
            {
                return ex.Message;
            }
        }

        /// <summary>
        /// 代付查询
        /// </summary>
        /// <returns></returns>
        public string SelectSubmit()
        {
            try
            {
                int Entrustid = 0;//出款订单ID
                Dictionary<String, String> paramdic = new Dictionary<String, String>();
                paramdic.Add("merchNo", userId);
                paramdic.Add("orderNo", DateTime.Now.ToString("yyyyMMdd_") + Entrustid);
                paramdic.Add("amount", 100.ToString());
                paramdic.Add("remitDate",DateTime.Now.ToString("yyyy-MM-dd"));

                paramdic = paramdic.OrderBy(o => o.Key).ToDictionary(o => o.Key, pp => pp.Value);

                string sourceStr = "{";
                foreach (KeyValuePair<string, string> v in paramdic)
                {
                    sourceStr += string.Format("\"{0}\":\"{1}\",", v.Key, v.Value);
                }
                sourceStr = sourceStr.Substring(0, sourceStr.Length - 1) + "}" + "MD5Key";
                paramdic.Add("sign", MD5Encrypt.MD5(sourceStr, false).ToUpper());

                paramdic = paramdic.OrderBy(o => o.Key).ToDictionary(o => o.Key, pp => pp.Value);
                sourceStr = "{";
                foreach (KeyValuePair<string, string> v in paramdic)
                {
                    sourceStr += string.Format("\"{0}\":\"{1}\",", v.Key, v.Value);
                }
                sourceStr = sourceStr.Substring(0, sourceStr.Length - 1) + "}";

                string publicKey = "RSA代付公钥";// base.GateUserEmail;
                string cipher_data = "";
                publicKey = RSAEncodHelper.RSAPublicKeyJava2DotNet(publicKey);

                byte[] cdatabyte = RSAEncodHelper.RSAPublicKeySignByte(sourceStr, publicKey);
                cipher_data = Convert.ToBase64String(cdatabyte);

                string paramstr = "data=" + System.Web.HttpUtility.UrlEncode(cipher_data) + "&merchNo=" + userId + "&version=" + version;

                //string p = HttpUtil.BuildQuery(paramdic, true);
                string strResult = HttpPost.SendPost("http://127.0.0.1/api/queryRemitResult.action", paramstr, 5000);

                return strResult;
            }
            catch (Exception ex)
            {
                return ex.Message;
            }
        }
        /// <summary>
        /// 查询返回
        /// </summary>
        /// <param name="strResult"></param>
        /// <returns></returns>
        public string SelectCallBackHandle(string strResult)
        {
            try
            {
                Dictionary<string, string> paramsDic = new Dictionary<string, string>();
                LitJson.JsonData jd = JsonMapper.ToObject(strResult);
                if (jd == null)
                    return "";
                if ((string)jd["stateCode"] != "00")
                {
                    return string.Format("错误,响应码:{0},响应消息:{1}", (string)jd["stateCode"], (string)jd["msg"]);
                }
                paramsDic.Add("merchNo", (string)jd["merchNo"]);
                paramsDic.Add("stateCode", (string)jd["stateCode"]);
                paramsDic.Add("orderNo", (string)jd["orderNo"]);
                paramsDic.Add("amount", (string)jd["amount"]);
                paramsDic.Add("msg", (string)jd["msg"]);
                paramsDic.Add("remitStateCode", (string)jd["remitStateCode"]);

                paramsDic = paramsDic.OrderBy(o => o.Key).ToDictionary(o => o.Key, pp => pp.Value);
                string sourceStr = "{";
                foreach (KeyValuePair<string, string> v in paramsDic)
                {
                    sourceStr += string.Format("\"{0}\":\"{1}\",", v.Key, v.Value);
                }
                sourceStr = sourceStr.Substring(0, sourceStr.Length - 1) + "}" + "MD5Key";
                if (MD5Encrypt.MD5(sourceStr, false).ToUpper().Equals((string)jd["sign"]) == false)
                {
                    return "验签失败!";
                }
                //return string.Format("代付状态:{0},消息:{1}",(string)jd["remitStateCode"],ReturnStatusByCode((string)jd["remitStateCode"]));
                string result = string.Format("金额:{0},代付状态:{1}",(Convert.ToDecimal((string)jd["amount"])) / 100, (string)jd["remitStateCode"]);
                return result;
            }
            catch (Exception ex)
            {
                return ex.Message;
            }
        }
    }
}
