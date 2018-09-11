<%@ WebHandler Language="C#" Class="PaySvc" %>

using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

public class PaySvc : IHttpHandler {
     private HttpContext cnt;
    public void ProcessRequest(HttpContext context)
    {
        cnt = context;
        context.Response.ContentType = "text/plain";
        string action = WebHelper.GetQueryString("action");
        switch (action)
        {
            case "GetPayUrl": GetPayUrl(); break;//
        }
    }

    private void GetPayUrl()
    {
        var payModel = WebHelper.GetFormInt("payModel", 0);
        var url = "http://pay.bpescp.cn/gateway/pay";
        var paramMap = new Dictionary<string, object>();
        paramMap.Add("sdorderno", DateTime.Now.ToString("yyyyMMddHHmmssfff"));
        paramMap.Add("ip", WebHelper.GetClientIP());
        paramMap.Add("subject", "test");
        paramMap.Add("total_fee", "100");
        paramMap.Add("paytype", payModel);
        paramMap.Add("notify_url", "http://www.xxx.cn/callback.aspx");
        paramMap.Add("return_url", "http://www.xxx.cn/callback.aspx");
        paramMap.Add("customerid", "8001");
        var orderedMap = paramMap.OrderBy(t => t.Key).ToDictionary(t => t.Key, t => t.Value);
        var data = string.Empty;
        foreach (var map in orderedMap)
        {
            data += string.Format("{0}={1}&", map.Key, map.Value);
        }
        var signKey = SecrecyHelper.MD5Entrypt(data + "key=Znp0EDqXEEo89Sog4HFxgi3V2aJn1OPu");
        paramMap.Add("sign", signKey);
        data = string.Empty;
        var orderedMap2 = paramMap.OrderBy(t => t.Key).ToDictionary(t => t.Key, t => t.Value);
        foreach (var map in orderedMap2)
        {
            data += string.Format("{0}={1}&", map.Key, map.Value);
        }
        Logger.Info("支付前" + url + "?" + data.TrimEnd('&'));
        try
        {
            var result = Utility.GetWebClientPostData(url + "?" + data.TrimEnd('&'));
            Logger.Info("支付后" + result);
            var dictionary = JsonHelper.FromJson<Dictionary<string, object>>(result);
            if (dictionary == null || dictionary.Count == 0) return;
            if (!dictionary.ContainsKey("status")) return;
            if (dictionary["status"].ToString() == "1")
            {
                WriteResult(1, dictionary["data"].ToString(), "成功");
                return;
            }
            WriteResult(-111, "", dictionary["msg"].ToString());
        }
        catch (Exception ex)
        {
            Logger.Info("请求异常" + ex);
        }
    }

    /// <summary>
    /// 输入JSON返回值 
    /// 格式：{Status:失败(0)/成功(1)/未登录(-1),Data:数据,Msg:返回的消息}
    /// </summary>
    /// <param name="status">状态</param>
    /// <param name="data">数据</param>
    /// <param name="msg">内容</param>
    protected void WriteResult(int status, object data, string msg)
    {
        cnt.Response.Write(JsonHelper.ToJson<object>(new
        {
            status = status,
            data = data,
            msg = msg
        }));
    }
 
    public bool IsReusable {
        get {
            return false;
        }
    }

}