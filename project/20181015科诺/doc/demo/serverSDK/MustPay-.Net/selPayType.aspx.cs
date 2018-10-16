using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using Com.mustpay;
using mustpay.config;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
/* *
 *功能：MustPay统一下单选择支付方式页
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 *该代码仅供学习和研究MustPay接口使用，只是提供一个参考。
 */
namespace mustpay
{
    public partial class selPayType : System.Web.UI.Page
    {
        //商户订单号，商户网站订单系统中唯一订单号，必填
        public string out_trade_no = "";
        //商品名称，必填
        public string subject = "";
        //付款金额，必填（单位：分）
        public string total_fee = "";
        //商品展示的超链接，选填
        public string show_url = "";
        //商品描述，选填
        public string body = "";
        public string apps_id = PayConfig.APPS_ID;
        public string mer_id = PayConfig.MER_ID;
        protected void Page_Load(object sender, EventArgs e)
        {
             out_trade_no = Request.Form["orderId"].Trim();
             subject = Request.Form["goodsName"].Trim();
             total_fee = Request.Form["price"].Trim();
             show_url = Request.Form["showUrl"].Trim();
             body = Request.Form["goodsDesc"].Trim();

        }
        public string GetPrepayId()
        {
            string prepayID = "";//预支付ID
            string notify_url = PayConfig.NOTIFY_URL;
            string return_url = PayConfig.RETURN_URL;
            string user_id = "15888881234";
            string extra = "1231245";
            string sign_type = PayConfig.SIGN_TYPE;
            //把请求参数打包成数组并签名
            Dictionary<string, string> parameters = new Dictionary<string, string>();
            parameters.Add("apps_id", apps_id);
            parameters.Add("out_trade_no", out_trade_no);
            parameters.Add("mer_id", mer_id);
            parameters.Add("total_fee", total_fee);
            parameters.Add("subject", subject);
            parameters.Add("body", body);
            parameters.Add("notify_url", notify_url);
            parameters.Add("return_url", return_url);
            parameters.Add("show_url", show_url);
            parameters.Add("user_id", user_id);
            parameters.Add("extra", extra);
            string privateKeyPem = PayConfig.MER_PRIVATE_KEY;
            //将请求参数进行RSA签名
            string sign = mustpay.Util.MustpaySignature.RSASign(parameters, privateKeyPem, PayConfig.INPUT_CHARSET, false, "RSA");
            parameters.Add("sign", sign);
            parameters.Add("sign_type", sign_type);
            //请求MustPay获取预支付ID
            mustpay.Util.WebUtils webUtil = new mustpay.Util.WebUtils();
            string jsonString = webUtil.DoPost(PayConfig.ADD_ORDER_URL, parameters, PayConfig.INPUT_CHARSET);
            JObject jo = (JObject)JsonConvert.DeserializeObject(jsonString);
            string info = jo["info"].ToString();
            JObject prepay_id = (JObject)JsonConvert.DeserializeObject(info);
            return prepayID = prepay_id["prepay_id"].ToString();
        }
    }
}