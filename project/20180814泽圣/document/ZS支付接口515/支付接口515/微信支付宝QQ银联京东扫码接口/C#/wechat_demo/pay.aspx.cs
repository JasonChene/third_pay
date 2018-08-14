using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using Demo.Class;
namespace wechat_demo
{
    public partial class pay : System.Web.UI.Page
    {
        //具体含义  查看文档  
        public static string merchantCode = "1000000001";
        public static string md5key = "123456ADSEF";
        public static string result = "";
        public static string amount = "10";
        public static string goodsName = "ceshi";
        public static  string ip = "192.168.1.1";
        public static string isSupportCredit = "1";
        public static string lastPayTime = "20170617170217";   //必须是这种时间格式yyyyMMddHHmmss
        public static string model = "QR_CODE";
        public static string noticeUrl = "http://www.baidu.com";
        public static string orderCreateTime = "20161216103820"; //必须是这种时间格式yyyyMMddHHmmss
        public static string outOrderId = "40112025827210255009275442223320";
        //签名数据 必须这种格式 按照a-z排序
        public static string signValue = "amount=" + amount + "&isSupportCredit=" + isSupportCredit + "&merchantCode=" + merchantCode
            + "&noticeUrl=" + noticeUrl + "&orderCreateTime=" + orderCreateTime + "&outOrderId=" + outOrderId + "&KEY=" + md5key;
        public static string sign = MD5.signMD5(signValue);
       
        protected void Page_Load(object sender, EventArgs e)
        {
            sendPostKeyValue();
        }
        public static string sendPostKeyValue() {

          // result = result + sign;
            System.Net.WebClient WebClientObj = new System.Net.WebClient();
            System.Collections.Specialized.NameValueCollection PostVars = new System.Collections.Specialized.NameValueCollection();
            PostVars.Add("amount", amount);
            PostVars.Add("ext", "");
            PostVars.Add("goodsExplain", "");
            PostVars.Add("goodsMark", "");
            PostVars.Add("goodsName", goodsName);
            PostVars.Add("ip", ip);
            PostVars.Add("isSupportCredit", isSupportCredit);
            PostVars.Add("lastPayTime", lastPayTime);
            PostVars.Add("merchantCode", merchantCode);
            PostVars.Add("model", model);
            PostVars.Add("noticeUrl", noticeUrl);
            PostVars.Add("orderCreateTime", orderCreateTime);
            PostVars.Add("outOrderId", outOrderId);
			PostVars.Add("payChannel", "21"); //21微信  30支付宝 
            PostVars.Add("sign", sign);
            try
            {
                byte[] byRemoteInfo = WebClientObj.UploadValues("", "POST", PostVars);
                //下面都没用啦，就上面一句话就可以了
                string sRemoteInfo = System.Text.Encoding.Default.GetString(byRemoteInfo);
                //这是获取返回信息
                result += sRemoteInfo;
              
            }
            catch
            {
                return result;
            }
            return result;
           
        } 
    }
}