using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.IO;
using Demo.Class;
namespace wechat_demo
{
    public partial class nitice : System.Web.UI.Page
    {
       
         
        protected void Page_Load(object sender, EventArgs e)
        {
          
               //处理传输过来的数据
          //像这样获取数据          
            string merchantCode = Request.Params["merchantCode"];
            string instructCode = Request.Params["instructCode"];
            string transType = Request.Params["transType"];
            string outOrderId = Request.Params["outOrderId"];
            string transTime = Request.Params["transTime"];
            string totalAmount = Request.Params["totalAmount"];
            string sign = Request.Params["sign"];
            //验签
            string signSrc = "instructCode=" + instructCode + "&merchantCode=" + merchantCode
                + "&outOrderId=" + outOrderId + "&totalAmount=" + totalAmount + "&transTime="
                + transTime + "&transType=" + transType;
            string origSign = MD5.signMD5(signSrc);
            if (origSign.Equals(sign))
            {
                Response.Write("{\"code\":\"00\"}");
            }

        }
    }
}