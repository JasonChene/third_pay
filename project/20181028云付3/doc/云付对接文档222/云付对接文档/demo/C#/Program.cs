//////////////////////////////////////////////////////////////////////////
/// Author:MaoWenjun
/// Date:2015-3-11 7:32 PM
/// FileName:Program.cs
/// Project:ConsoleApplication1
/// Namespace:ConsoleApplication1
//////////////////////////////////////////////////////////////////////////


using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using NewLife.Cryptography;

using System.ComponentModel;
using System.Data;



using System.Threading.Tasks;

using System.Security.Cryptography;


namespace ConsoleApplication1
{
    class Program
    {
        private static HttpHelper http = new HttpHelper();
        private static HttpPara httpParam = new HttpPara();

        //这个可以 跟商户 id  登陆平台 查看商户信息 即可
        private static string  S_KEY="23A9E4D3227BB8";

        //商户 id
        private static string account_id = "10008";

 

        static void Main(string[] args)
        {
            httpParam.Accept = "*/*";
            httpParam.ContentType = "application/x-www-form-urlencoded; Charset=UTF-8";
            httpParam.UserAgent = "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)";
         

            string url = "http://47.105.34.228/gateway/index/checkpoint.do";
         
            string out_trade_no = "201806261212440"; //订单号
            string amount = "0.01"; //用户支付的金额   传过来的参数必须为string 且 格式为 1000.00含两位小数   单笔金额<=50000.00的

            string signStr = getSign(getAmount(amount), out_trade_no);

            Console.WriteLine("Sign Result：{0}", signStr);
           

            string data = "robin=2&callback_url=http://47.105.34.228/&error_url=http://47.105.34.228/&amount=" + amount + "&account_id=" + account_id + "&out_trade_no=" + out_trade_no + "&content_type=json&sign=" + signStr + "&keyId=&type=1&thoroughfare=alipay_auto&success_url=http://47.105.34.228/";
     
            String str = http.PostHtml(url, data, httpParam, Encoding.GetEncoding("UTF-8"));
            Console.WriteLine("Http Result：{0}", str);
        
            Console.ReadLine();
        }



        /**
         * 获取  sign
         * amount  订单金额
         * orderNo 订单号
         * */
        private static string getSign(string amount,string orderNo) {
            RC4Crypto rc4 = new RC4Crypto();
            string data = amount + orderNo;
            Console.WriteLine("Data：{0}", data);
            string md5Str = GetMd5Str(data);
            Console.WriteLine("Md5Str：{0}", md5Str);
            byte[] byts = rc4.EncryptEx(Encoding.UTF8.GetBytes(md5Str), S_KEY);
            string rc4Str = GetMd5Str(byts);
            Console.WriteLine("rc4Str：{0}", rc4Str);
            return rc4Str;
        }


        public static string GetMd5Str(string ConvertString)
        {
            MD5CryptoServiceProvider md5 = new MD5CryptoServiceProvider();
            string t2 = BitConverter.ToString(md5.ComputeHash(UTF8Encoding.Default.GetBytes(ConvertString)));
            t2 = t2.Replace("-", "").ToLower();
            return t2;
        }

        public static string GetMd5Str(byte[] byts)
        {
            MD5CryptoServiceProvider md5 = new MD5CryptoServiceProvider();
            string t2 = BitConverter.ToString(md5.ComputeHash(byts));
            t2 = t2.Replace("-", "").ToLower();
            return t2;
        }
		
		public static string getAmount(string amount) {
			int len = amount.Length;
			string result = null;
			if(len >= 8) {
				result = amount.Insert(2,",");
			}
			else if(len >= 7) {
				result = amount.Insert(1,",");
			}
			else {
				result = amount;
			}
			return result;
		}
    }
}
