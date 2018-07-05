using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Text;
using System.Security.Cryptography;

namespace JRAPI_NET_DEMO
{
    public partial class Default : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (!this.IsPostBack) {
                txtordernumber.Text = DateTime.Now.ToString("yyyyMMddHHmmssfff");
            }
        }

        protected void btnSub_Click(object sender, EventArgs e)
        {
            string apiurl = txtUrl.Text;//提交地址
            string version = txtversion.Text;//接口版本号,目前固定值为3.0
            string method = txtmethod.Text;//接口名称: Jr.online.interface
            string partner = txtpartner.Text;//商户id,由精睿API分配
            string banktype = txtbanktype.Text;//银行类型 default为跳转到精睿接口进行选择支付
            string paymoney = txtpaymoney.Text;//单位元（人民币）,两位小数点
            string ordernumber = txtordernumber.Text;//商户系统订单号，该订单号将作为精睿接口的返回数据。该值需在商户系统内唯一
            string callbackurl = txtcallbackurl.Text;//下行异步通知的地址，需要以http://开头且没有任何参数
            string hrefbackurl = txthrefbackurl.Text;//下行同步通知过程的返回地址(在支付完成后精睿接口将会跳转到的商户系统连接地址)。注：若提交值无该参数，或者该参数值为空，则在支付完成后，精睿接口将不会跳转到商户系统，用户将停留在精睿接口系统提示支付成功的页面。
            string goodsname = txtgoodsname.Text;//商品名称
            string attach = txtattach.Text;//备注信息，下行中会原样返回。若该值包含中文，请注意编码  
            string isshow = txtisShow.Text;//该参数为支付宝扫码、微信、QQ钱包专用，默认为1，跳转到网关页面进行扫码，如设为0，则网关只返回二维码图片地址供用户自行调用
            string key = txtKey.Text;
            string signSource = string.Format("version={0}&method={1}&partner={2}&banktype={3}&paymoney={4}&ordernumber={5}&callbackurl={6}{7}",version,method,partner, banktype, paymoney, ordernumber, callbackurl, key);
            string sign = JRAPICommon.MD5(signSource, false);//32位小写MD5签名值
            Dictionary<string, string> dict = new Dictionary<string, string>();
            dict.Add("partner", partner);
            dict.Add("version", version);
            dict.Add("method", method);
            dict.Add("banktype", banktype);
            dict.Add("paymoney", paymoney);
            dict.Add("ordernumber", ordernumber);
            dict.Add("callbackurl", callbackurl);
            dict.Add("hrefbackurl", hrefbackurl);
            dict.Add("goodsname", goodsname);
            dict.Add("attach", attach);
            dict.Add("isshow", isshow);
            dict.Add("sign", sign);
            string formStr = JRAPICommon.getSubmitForm(dict,"post",apiurl);
            formStr += "<script>document.forms[0].submit();</script>";
            Response.Write(formStr);

        }
 
    }
}