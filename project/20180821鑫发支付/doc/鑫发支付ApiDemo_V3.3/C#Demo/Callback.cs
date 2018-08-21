using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace testconsole
{
    public class Callback
    {
        public void call()
        {
            //try
            //{
            //    //模拟post请求
            //    string orderNo = Request["orderNo"];
            //    //参数判断
            //    if (string.IsNullOrEmpty(orderNo))
            //    {
            //        Log.WriteTextLog("RuiFuCallBackInfo", "商户返回订单号orderNo为空", DateTime.Now);
            //        Response.Write("error");
            //        Response.End();
            //    }

            //    if (string.IsNullOrEmpty(Request["data"]))
            //    {
            //        Log.WriteTextLog("RuiFuCallBackInfo", "商户返回data为空", DateTime.Now);
            //        Response.Write("error");
            //        Response.End();
            //    }
            //    //获取订单
            //    int orderId = ZF.Common.TypeConverter.StrToInt(orderNo.Substring(9));
            //    O_Orders order = OOrdersDal.GetModel(orderId);
            //    if (order == null)
            //    {
            //        Log.WriteTextLog("RuiFuCallBackInfo", "找不到订单数据，订单号：" + orderId, DateTime.Now);
            //        Response.Write("the order is null");
            //        Response.End();
            //    }
            //    //网关对应账号信息数据
            //    var netmodel = CNetGatePassportListDal.GetModel(order.nuid, "GateUserKey,RSAPublicKey,RSAPrivateKey,RSAPrivateDecodeKey");
            //    if (netmodel == null)
            //    {
            //        //Log.WriteTextLog("RuiFuCallBackInfo", "找不到网关账号信息" + orderId, DateTime.Now);
            //        Response.Write("the netmodel is null");
            //        Response.End();
            //    }
            //    //CNetGatePassportListDal.GetList("")
            //    string data = Request["data"];

            //    string privateKey = "加密私钥";//netmodel.RSAPrivateKey;

            //    string result = RSAHelper.decryptData(data, privateKey, "utf-8");
            //    Dictionary<string, string> paramsDic = new Dictionary<string, string>();
            //    LitJson.JsonData jd = JsonMapper.ToObject(result);
            //    if (jd == null)
            //    {
            //        Log.WriteTextLog("RuiFuCallBackInfo", "商户返回串解析返回为空", DateTime.Now);
            //        Response.Write("error");
            //        Response.End();
            //    }
            //    //20170808_

            //    //md5 key
            //    string GateUserKey = "商户ID";

            //    //if (netmodel != null)
            //    //{
            //    //    GateUserKey = netmodel.GateUserKey;
            //    //}
            //    //else
            //    //{
            //    //    GateUserKey = gateModel.GateUserKey;
            //    //}
            //    paramsDic.Add("amount", (string)jd["amount"]);
            //    paramsDic.Add("goodsName", (string)jd["goodsName"]);
            //    paramsDic.Add("merchNo", (string)jd["merchNo"]);
            //    paramsDic.Add("payType", (string)jd["payType"]);
            //    paramsDic.Add("orderNo", (string)jd["orderNo"]);
            //    paramsDic.Add("payDate", (string)jd["payDate"]);
            //    paramsDic.Add("payStateCode", (string)jd["payStateCode"]);
            //    paramsDic = paramsDic.OrderBy(o => o.Key).ToDictionary(o => o.Key, pp => pp.Value);
            //    string sourceStr = "{";
            //    foreach (KeyValuePair<string, string> v in paramsDic)
            //    {
            //        sourceStr += string.Format("\"{0}\":\"{1}\",", v.Key, v.Value);
            //    }
            //    sourceStr = sourceStr.Substring(0, sourceStr.Length - 1) + "}" + GateUserKey;
            //    if (MD5Encrypt.MD5(sourceStr, false).ToUpper().Equals((string)jd["sign"]) == false)
            //    {
            //        Response.Write("error");
            //        Response.End();
            //    }
            //    int orderstatus = 2;
            //    if ((string)jd["payResult"] == "00")
            //    {
            //        orderstatus = 1;
            //    }
            //   //更新本地订单号

            //    Response.Write("SUCCESS");
            //    Response.End();
            //}
            //catch (Exception ex)
            //{
            //    Response.Write(ex.Message);
            //}
        }
    }
}
