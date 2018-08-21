using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Web;
using LitJson;
namespace testconsole
{
    public class PayDemo
    {
        private string dateStr = DateTime.Now.ToString("yyyyMMdd_");
        /// <summary>
        /// 入款支付
        /// </summary>
        private string PaySubmit()
        {
            string payurl = "http://127.0.0.1/api/pay";//请求地址
            string userId = "XF201808160001";//商户ID
            string orderNo = dateStr + "12345";
            Dictionary<String, String> paramdic = new Dictionary<String, String>();
            paramdic.Add("version", "V3.3.0.0");
            paramdic.Add("merchNo", userId);
            paramdic.Add("payType", "WX");
            paramdic.Add("randomNum", new Random().Next(1000, 9999).ToString());//随机数 可以重复
            paramdic.Add("orderNo", orderNo);//订单交易号
            paramdic.Add("amount", "100");//订单金额 单位分
            paramdic.Add("goodsName", "iPhone配件");//商品名称
            paramdic.Add("notifyUrl", "http://www.xxx.com/api/callback.aspx");//支付结果异步通知地址 
            paramdic.Add("notifyViewUrl", "http://www.xxx.com/api/callback.aspx");//回显地址
            paramdic.Add("charsetCode", "UTF-8");

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

            string publicKey = "RSA支付公钥";// base.GateUserEmail;
            string cipher_data = "";
            publicKey = RSAEncodHelper.RSAPublicKeyJava2DotNet(publicKey);

            byte[] cdatabyte = RSAEncodHelper.RSAPublicKeySignByte(sourceStr, publicKey);
            cipher_data = Convert.ToBase64String(cdatabyte);

            string paramstr = "data=" + System.Web.HttpUtility.UrlEncode(cipher_data) + "&merchNo=" + userId;

            //string p = HttpUtil.BuildQuery(paramdic, true);
            string strResult = HttpPost.SendPost(payurl, paramstr, 5000);

            //支付返回
            #region 支付返回
            try
            {
                /*
                 data={“key1":"val1","key2":"val2","sign":"1111111111111"}
                    key= AiYLumB03Fingt3R3ULdvFzS
                    metaSign={“key1":"val1","key2":"val2"}key,去除 sign 字段
                    sign=MD5(metaSign)
                 */
                //strResult="{\"merchNo\":\"XF201808160001\",\"msg\":\"提交成功\",\"orderNo\":\"20180801102543215VCqeRK\",\"qrcodeUrl\":\"https://qr.alipay.com/bax04012imdorbvgrls78018\",\"sign\":\"96DCC7A5140BF58CD02D18D518B770E9\",\"stateCode\":\"00\"}";
                if (string.IsNullOrEmpty(strResult))
                    return "";

                Dictionary<string, string> paramsDic = new Dictionary<string, string>();
                LitJson.JsonData jd = JsonMapper.ToObject(strResult);
                if (jd == null)
                    return "";
                if ((string)jd["stateCode"] != "00")
                {
                    return (string)jd["msg"];
                }
                paramsDic.Add("merchNo", (string)jd["merchNo"]);
                paramsDic.Add("stateCode", (string)jd["stateCode"]);
                paramsDic.Add("msg", (string)jd["msg"]);
                paramsDic.Add("orderNo", (string)jd["orderNo"]);
                paramsDic.Add("qrcodeUrl", (string)jd["qrcodeUrl"]);

                paramsDic = paramsDic.OrderBy(o => o.Key).ToDictionary(o => o.Key, pp => pp.Value);
                sourceStr = "{";
                foreach (KeyValuePair<string, string> v in paramsDic)
                {
                    sourceStr += string.Format("\"{0}\":\"{1}\",", v.Key, v.Value);
                }
                sourceStr = sourceStr.Substring(0, sourceStr.Length - 1) + "}" + "MD5Key";
                if (MD5Encrypt.MD5(sourceStr, false).ToUpper().Equals((string)jd["sign"]) == false)
                {
                    //Log.WriteTextLog("RuiFuPayInfo", "PayCallBackHandle验签失败:返回原文:" + sourceStr + "；返回密钥：" + (string)jd["sign"] + "", DateTime.Now);
                    return "验签失败!" + strResult;
                }
                return (string)jd["qrcodeUrl"];
            }
            catch (Exception ex)
            {
                //Log.WriteTextLog("RuiFuPayInfo", "PayCallBackHandle失败：:" + ex.Message, DateTime.Now);
                return ex.Message;
            }
            #endregion
        }
        //入款支付查询
        private string PaySelect()
        {
            try
            {
                string orderSelectUrl = "http://127.0.0.1/api/queryPayResult";
                string paytype = "ZFB";
                string userId = "XF201808160001";
                Dictionary<String, String> paramdic = new Dictionary<String, String>();
                paramdic.Add("merchNo", userId);
                paramdic.Add("payType", paytype);
                paramdic.Add("orderNo", dateStr + "test");//订单交易号
                paramdic.Add("amount", "100");//订单金额
                paramdic.Add("goodsName", "iPhone配件");//商品名称
                paramdic.Add("payDate", DateTime.Now.ToString("yyyy-MM-dd"));
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

                string publicKey = "RSA支付公钥";// base.GateUserEmail;
                string cipher_data = "";
                publicKey = RSAEncodHelper.RSAPublicKeyJava2DotNet(publicKey);

                byte[] cdatabyte = RSAEncodHelper.RSAPublicKeySignByte(sourceStr, publicKey);
                cipher_data = Convert.ToBase64String(cdatabyte);

                string paramstr = "data=" + System.Web.HttpUtility.UrlEncode(cipher_data) + "&merchNo=" + userId;

                //string p = HttpUtil.BuildQuery(paramdic, true);
                string strResult = HttpPost.SendPost(orderSelectUrl, paramstr, 5000);


                #region 查询返回
                LitJson.JsonData jd = JsonMapper.ToObject(strResult);
                if (jd == null)
                    return "";
                if ((string)jd["stateCode"] != "00")
                {
                    return string.Format("查询失败,状态:{0},消息:{1}", (string)jd["stateCode"], (string)jd["msg"]);
                }

                Dictionary<string, string> paramsDic = new Dictionary<string, string>();
                paramsDic.Add("merchNo", (string)jd["merchNo"]);
                paramsDic.Add("msg", (string)jd["msg"]);
                paramsDic.Add("stateCode", (string)jd["stateCode"]);
                paramsDic.Add("orderNo", (string)jd["orderNo"]);
                paramsDic.Add("payStateCode", (string)jd["payStateCode"]);

                paramsDic = paramsDic.OrderBy(o => o.Key).ToDictionary(o => o.Key, pp => pp.Value);
                sourceStr = "{";
                foreach (KeyValuePair<string, string> v in paramsDic)
                {
                    sourceStr += string.Format("\"{0}\":\"{1}\",", v.Key, v.Value);
                }
                sourceStr = sourceStr.Substring(0, sourceStr.Length - 1) + "}" + userId;
                if (MD5Encrypt.MD5(sourceStr, false).ToUpper().Equals((string)jd["sign"]) == false)
                {
                    return "验签失败!";
                }
                return string.Format("订单状态:{0}(00:支付成功;06:初始,50:网络异常,04:其他错误,03:签名错误,01:失败,99:未支付,05:未知),消息:{1}", (string)jd["payStateCode"], ReturnStatusByCode((string)jd["payStateCode"]));
                #endregion
            }
            catch (Exception ex)
            {
                return ex.Message;
            }
        }
        /// <summary>
        /// 根据状态返回对应文本
        /// </summary>
        /// <param name="code"></param>
        /// <returns></returns>
        public string ReturnStatusByCode(string code)
        {
            string result = "支付成功";
            switch (code)
            {
                case "06":
                    result = "初始";
                    break;
                case "50":
                    result = "网络异常";
                    break;
                case "04":
                    result = "其他错误";
                    break;
                case "03":
                    result = "签名错误";
                    break;
                case "01":
                    result = "失败";
                    break;
                case "99":
                    result = "未支付";
                    break;
                case "05":
                    result = "未知";
                    break;
            }
            return result;
        }
    }
}
