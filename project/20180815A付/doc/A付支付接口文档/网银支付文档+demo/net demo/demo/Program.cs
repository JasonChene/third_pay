using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;

namespace demo
{
    class Program
    {
        static void Main(string[] args)
        {
            //支付产品说明:
            //10000101 微信T1扫码支付  10000102 微信D0扫码支付  10000103  微信T0扫码支付
            //10000301 微信T1公众号支付  10000302 微信D0公众号支付  10000303  微信T0公众号支付
            //20000301 支付宝T1扫码支付  20000302 支付宝D0扫码支付  20000303  支付宝T0扫码支付
            //40000501 网关快捷T1支付  40000502  网关快捷D0支付  40000503  网关快捷T0支付

            #region 扫码、公众号支付
            //SortedDictionary<string, string> _params = new SortedDictionary<string, string>();
            //_params.Add("payKey", "1add3c5fd25541dba230fbbcd33985c2");                        //商户支付编号
            //_params.Add("productType", "70000203");                                           //支付产品 10000103：微信扫码T0  20000303：支付宝T0扫码  70000203：QQ钱包T0扫码
            //_params.Add("orderPrice", "10.01");                                                //金额
            //_params.Add("outTradeNo", DateTime.Now.ToString("yyyyMMddHHmmssfff"));            //商户订单号
            //_params.Add("orderTime", DateTime.Now.ToString("yyyyMMddHHmmss"));                //支付时间
            //_params.Add("productName", "测试产品");                                           // 商品名称
            //_params.Add("orderIp", "127.0.0.1");                                              //支付ip
            //_params.Add("returnUrl", "https://gateway.aabill.net/cnpPayNotify/returnNotify");   //同步返回地址
            //_params.Add("notifyUrl", "https://gateway.aabill.net/cnpPayNotify/notify");         //异步返回地址
            //_params.Add("remark", "支付备注");                                                //备注
            //_params.Add("openId", "");                                                        //openid,公众号支付必填
            //_params.Add("subPayKey", "");                                                     //子商户支付编号
            //_params.Add("sign","aaa");
            //_params.Add("sign", Sign(_params, "4f48314d79c347fd8c84d66cd93a1d10"));           //参数签名,防止篡改

            //Console.WriteLine("");
            //Console.WriteLine("开始请求服务器,请稍等...");
            //string response = Post("https://gateway.aabill.net/cnpPay/initPay", _params);
            //Console.WriteLine("结束请求服务器,请稍等...");
            //Console.WriteLine(response);

            #endregion

            #region 网管快捷支付
            //SortedDictionary<string, string> _params = new SortedDictionary<string, string>();
            //_params.Add("productType", "40000501"); //40000501 T1网关快捷
            //_params.Add("payKey", "1add3c5fd25541dba230fbbcd33985c2");// 商户支付Key
            //_params.Add("orderPrice", "5");
            //_params.Add("payBankAccountNo", "6214832011757825");//支付银行卡
            //_params.Add("outTradeNo", DateTime.Now.ToString("yyyyMMddHHmmssfff"));
            //_params.Add("productName", "水杯");// 商品名称
            //_params.Add("orderIp", "127.0.0.1");// 下单IP
            //_params.Add("orderTime", DateTime.Now.ToString("yyyyMMddHHmmss"));// 订单时间
            //_params.Add("returnUrl", "https://gateway.aabill.net/cnpPayNotify/returnNotify");
            //_params.Add("notifyUrl", "https://gateway.aabill.net/cnpPayNotify/notify");
            //_params.Add("subPayKey", "");
            //_params.Add("remark", "支付备注");

            ////H5快捷必要参数
            //_params.Add("payPhoneNo", "18007470252");
            //_params.Add("payBankAccountName", "张三");
            //_params.Add("payCertNo", "430426198607103477");
            //_params.Add("sign", Sign(_params, "4f43321d79c347fd8c84d66cd93a1d10"));

            //Console.WriteLine("");
            //Console.WriteLine("开始请求服务器,请稍等...");

            ////string response = Post("https://gateway.aabill.net/quickGateWayPay/initPay", _params);
            ////Console.WriteLine(response);

            //StringBuilder sb = new StringBuilder();
            //foreach (var item in _params) {
            //    sb.Append("&" + item.Key + "=" + item.Value);
            //}
            //string response = "https://tgateway.rffbe.top/quickGateWayPay/initPay?" + sb.ToString().TrimStart('&');
            //Console.WriteLine(response);
            //Console.WriteLine("结束请求服务器,请稍等...");


            #endregion

            #region 查询订单
            //SortedDictionary<string, string> _params = new SortedDictionary<string, string>();
            //_params.Add("payKey", "f5dae548a7014125b0d4bed12b39fe59");
            //_params.Add("outTradeNo", "20170707001018259");
            //_params.Add("sign", Sign(_params, "cc372df01e1244d29a99b4f72ca5bf07"));

            //Console.WriteLine("");
            //Console.WriteLine("开始请求服务器,请稍等...");
            //string response = Post("https://gateway.aabill.net/query/singleOrder", _params);
            //Console.WriteLine("结束请求服务器,请稍等...");
            //Console.WriteLine(response);

            #endregion


            #region 签名验证
            //服务器异步回调示例：http://debug.iexbuy.cn/test-pay-app-notify/pay/notify?orderPrice=11.01&orderTime=20170914165648&outTradeNo=1505379408349&payKey=8b96ba93c5a14b27a3cdd596f87e0173&productName=hjj测试商城消费&productType=40000503&successTime=20170914170148&tradeStatus=SUCCESS&trxNo=TES77772017091410002914&sign=1B8F82AE201FE93F256F88D5360A7FCE
            //实际情况请按照相应的方式获取参数值,比如：request.getparameter()等。

            //SortedDictionary<string, string> _params = new SortedDictionary<string, string>();
            //_params.Add("orderPrice", "11.01");
            //_params.Add("orderTime", "20170914165648");
            //_params.Add("outTradeNo", "1505379408349");
            //_params.Add("payKey", "8b96ba93c5a14b27a3cdd596f87e0173");
            //_params.Add("productName", "hjj测试商城消费");
            //_params.Add("productType", "40000503");
            //_params.Add("successTime", "20170914170148");
            //_params.Add("tradeStatus", "SUCCESS");
            //_params.Add("trxNo", "TES77772017091410002914");
            //_params.Add("sign", "1B8F82AE201FE93F256F88D5360A7FCE");

            //Console.WriteLine("签名验证结果:" + Verify(_params, "c25c31031b064d84875babeec9d20fa1"));

            #endregion


            #region 代付
            //SortedDictionary<string, string> _params = new SortedDictionary<string, string>();
            //_params.Add("bankAccountType", "PRIVATE_DEBIT_ACCOUNT");//对公对私标识	对公：PUBLIC_ACCOUNT、对私：PRIVATE_DEBIT_ACCOUNT
            //_params.Add("bankBranchName", "中国建银行股份有限公司中山东区支行");//代付开户行支行名称***
            //_params.Add("bankBranchNo", "105603000768");//代付开户银行支行行号
            //_params.Add("bankClearNo", "");//代付开户银行清算行行号
            //_params.Add("bankCode", "CCB");//代付开户行编码
            //_params.Add("bankName", "建设银行");//代付开户行名称***
            //_params.Add("certNo", "");//代付证件号
            //_params.Add("certType", "IDENTITY");//代付证件类型
            //_params.Add("city", "");//代付开户行城市
            //_params.Add("orderPrice", "3.01");// 代付订单金额 , 单位:元
            //_params.Add("outTradeNo", DateTime.Now.ToString("yyyyMMddHHmmssfff"));//商户代付请求号
            //_params.Add("payKey", "f5dae548a7014125b0d4bed12b39fe59");// 商户支付Key
            //_params.Add("phoneNo", "");//代付银行手机号
            //_params.Add("productType", "WEIXIN");// 产品类型 	微信：WEIXIN、支付宝：ALIPAY、快捷支付：QUICKPAY、代扣支付：DEDUCTPAY、B2C支付：B2CPAY、代付：ANOTHER_PAY、银联支付：UNION_PAY、QQ支付：QQ_PAY、京东支付：JD_PAY
            //_params.Add("province", "");//代付开户行省份
            //_params.Add("proxyType", "T0");// 交易类型
            //_params.Add("receiverAccountNo", "");//代付账号
            //_params.Add("receiverName", "");//代付账户名 收款人账户名
            //_params.Add("remit", "");//备注
            //_params.Add("sign", Sign(_params, "cc372df01e1244d29a99b4f72ca5bf07"));

            //Console.WriteLine("");
            //Console.WriteLine("开始请求服务器,请稍等...");
            //string response = Post("https://gateway.aabill.net/accountProxyPay/initPay", _params);
            //Console.WriteLine("结束请求服务器,请稍等...");
            //Console.WriteLine(response);

            #endregion

            #region 代付订单查询
            //SortedDictionary<string, string> _params = new SortedDictionary<string, string>();
            //_params.Add("payKey", "f5dae548a7014125b0d4bed12b39fe59");
            //_params.Add("outTradeNo", "1501493846376");
            //_params.Add("sign", Sign(_params, "cc372df01e1244d29a99b4f72ca5bf07"));

            //Console.WriteLine("");
            //Console.WriteLine("开始请求服务器,请稍等...");
            //string response = Post("https://gateway.aabill.net/proxyPayQuery/query", _params);
            //Console.WriteLine("结束请求服务器,请稍等...");
            //Console.WriteLine(response);

            #endregion



            Console.WriteLine("请按任意键退出程序...");
            Console.ReadKey();
        }


        /// <summary>
        /// 签名验证
        /// </summary>
        /// <param name="_params">参数集</param>
        /// <param name="key">用户秘钥</param>
        /// <returns></returns>
        public static bool Verify(SortedDictionary<string, string> _params,string key) {
            string sign = _params["sign"];
            return sign.Equals(Sign(_params, key));
        }

        /// <summary>
        /// 签名
        /// </summary>
        /// <param name="_params">参数集</param>
        /// <param name="key">用户秘钥</param>
        /// <returns></returns>
        public static string Sign(SortedDictionary<string, string> _params,string key) 
        {
            //去除参数集中的sign
            if (_params.ContainsKey("sign")) {
                _params.Remove("sign");
            }

            //拼接原签名串
            StringBuilder sb = new StringBuilder();
            foreach (var item in _params) {
                if (!item.Key.Equals("sign") && !String.IsNullOrEmpty(item.Value))
                {
                    sb.Append(item.Key + "=" + item.Value + "&");
                }
            }
            sb.Append("paySecret=" + key);
            Console.WriteLine("待签名字符串:" + sb.ToString());

            //加密转全大写
            return Md5(sb.ToString(),32).ToUpper();
            
        }

        /// <summary>
        /// MD5加密
        /// </summary>
        /// <param name="str">待加密字符串</param>
        /// <param name="code">保留长度</param>
        /// <returns></returns>
        public static string Md5(string str, int code)
        {
            string strEncrypt = string.Empty;
            if (code == 16)
            {
                strEncrypt = System.Web.Security.FormsAuthentication.HashPasswordForStoringInConfigFile(str, "MD5").Substring(8, 16);
            }

            if (code == 32)
            {
                strEncrypt = System.Web.Security.FormsAuthentication.HashPasswordForStoringInConfigFile(str, "MD5");
            }

            return strEncrypt;
        }


        #region POST请求实现

        /// <summary>
        /// POST请求
        /// </summary>
        /// <param name="Uri">Uri地址</param>
        /// <param name="ht">请求参数</param>
        /// <returns></returns>
        public static string Post(string uri, SortedDictionary<string, string> _params = null, string ContentType = "application/x-www-form-urlencoded")
        {
            string strResponse = "";
            try
            {
                //使用HttpWebRequest类的Create方法创建一个请求到uri的对象。
                HttpWebRequest request = (HttpWebRequest)HttpWebRequest.Create(uri);
                //指定请求的方式为POST方式
                request.Method = WebRequestMethods.Http.Post;

                request.ContentType = "application/x-www-form-urlencoded";

                string requestParam = "";

                if (_params != null)
                {
                    foreach (var item in _params)
                    {
                        requestParam += "&" + System.Web.HttpUtility.UrlEncode(item.Key.ToString()) + "=" + System.Web.HttpUtility.UrlEncode(item.Value.ToString());
                    }
                    requestParam.TrimStart('&');
                }

                byte[] data = Encoding.ASCII.GetBytes(requestParam);
                using (Stream stream = request.GetRequestStream())
                {
                    stream.Write(data, 0, data.Length);
                }

                //获取该请求所响应回来的资源，并强转为HttpWebResponse响应对象
                HttpWebResponse response = (HttpWebResponse)request.GetResponse();
                //获取该响应对象的可读流
                StreamReader reader = new StreamReader(response.GetResponseStream());
                //将流文本读取完成并赋值给str
                strResponse = reader.ReadToEnd();
                //关闭响应
                response.Close();

            }
            catch (Exception ex)
            {
                throw ex;
            }
            return strResponse;
        }

        #endregion

    }
}
