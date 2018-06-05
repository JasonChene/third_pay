using System;
using System.Collections.Generic;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Collections.Specialized;

namespace com.mobaopay.merchant
{
    public partial class Callback : System.Web.UI.Page
    {
        public string apiName;
        public string notifyTime;
        public string tradeAmt;
        public string merchNo;
        public string merchParam;
        public string orderNo;
        public string tradeDate;
        public string accNo;
        public string accDate;
        public string orderStatus;
        public string veryfyDesc;
        protected void Page_Load(object sender, EventArgs e)
        {
             if (!IsPostBack)
            {
                //此处可增加一个日志来记录通知数据，便于调试接口。

                Dictionary<string, string> dict = GetRequestPost();
                // 判断是否有带返回参数
                if (dict.Count > 0)
                {
                    // 验证签名，先获取到签名源字符串和签名字符串后，做签名验证。
                    string srcString = string.Format("apiName={0}&notifyTime={1}&tradeAmt={2}&merchNo={3}&merchParam={4}&orderNo={5}&tradeDate={6}&accNo={7}&accDate={8}&orderStatus={9}",
                            dict["apiName"],
                            dict["notifyTime"],
                            dict["tradeAmt"],
                            dict["merchNo"],
                            dict["merchParam"],
                            dict["orderNo"],
                            dict["tradeDate"],
                            dict["accNo"],
                            dict["accDate"],
                            dict["orderStatus"]);
                    string sigString = dict["signMsg"];
                    string notifyType = dict["notifyType"];
                    //if (Int32.Parse(notifyType) == 1)
                    //{
                    //    sigString = System.Web.HttpUtility.UrlDecode(sigString);
                    //}
                    sigString = sigString.Replace("\r", "").Replace("\n", "");
                    bool verifyResult = MobaopaySignUtil.Instance.verifyData(sigString, srcString);
                    veryfyDesc = verifyResult ? "签名验证通过" : "签名验证失败";

                    // 取出用于显示的各个数据，这里只是为了演示，实际应用中应该不需要把这些数据显示到页面上。
                    apiName = dict["apiName"];
                    notifyTime = dict["notifyTime"];
                    tradeAmt = dict["tradeAmt"];        //交易金额
                    merchNo = dict["merchNo"];          //商户号
                    merchParam = dict["merchParam"];    //商户参数，来自支付请求中的商户参数，原物返回，方便商户异步处理需要传递数据
                    orderNo = dict["orderNo"];          //商户订单号
                    tradeDate = dict["tradeDate"];      //商户交易日期
                    accNo = dict["accNo"];              //支付平台订单号
                    accDate = dict["accDate"];          //支付平台订单日期
                    orderStatus = dict["orderStatus"];  //订单状态：0-未支付，1-成功，2-失败；实际上只有成功才会发送通知

                    if (verifyResult)
                    {
                        /**
                         * 验证通过后，请在这里加上商户自己的业务逻辑处理代码.
                         * 比如：
                         * 1、根据商户订单号取出订单数据
                         * 2、根据订单状态判断该订单是否已处理（因为通知会收到多次），避免重复处理
                         * 3、比对一下订单数据和通知数据是否一致，例如金额等
                         * 4、接下来修改订单状态为已支付或待发货
                         * 5、...
                         */

                        if (Int32.Parse(notifyType) != 0)
                        {
                            /**
                             * notifyType！=0 表示当前通知来自服务器的异步通知
                             * 需要回写一个大写的SUCCESS字符串表明商户系统已收到通知
                             * 否则支付系统将在接下来的24小时内按一定的时间策略重复发送该通知
                             **/
                            Response.Redirect("notify.aspx", true);     // 回写‘SUCCESS’方式一： 重定向到一个专门用于处理回写‘SUCCESS’的页面，这样可以保证输出内容中只有'SUCCESS'这个字符串。
                            //Response.Write("SUCCESS");                // 回写‘SUCCESS’方式二： 直接让当前输出流中包含‘SUCCESS’字符串。两种方式都可以，但建议采用第一种。
                        }
                    }
                }
                else
                {
                    Response.Write("无通知参数");
                }
            }
        }

        private Dictionary<string, string> GetRequestPost()
        {
            Dictionary<string, string> dict = new Dictionary<string, string>();
            NameValueCollection coll = Request.Form;

            foreach (string s in coll.AllKeys)
            {
                dict.Add(s, coll.Get(s));
            }
            return dict;
        }
    }
}