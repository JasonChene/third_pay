using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace mustpay.config
{
    public class PayConfig
    {

        //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
        
        //////MustPay平台公钥
        public static String PLATE_PUBLIC_KEY = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDO7CQpYHhEonv1g9YjRVGJDaCOu0bXogD7pBLQu2dDvJ8TGROCEw6ArIWgAWEEE1uEShPBa4MpCP4ZMjT5RMj45o0pb0Z8s4k6CpS9D1LFK9msNpsN8PyaRDQC86R6jxAVQMWgfIZ9cxfZR8Ple3GJGjwBfeRnzh75rE1DHCBOcwIDAQAB";
        //商户私钥 pkcs1
        public static String MER_PRIVATE_KEY = "MIICWwIBAAKBgQCggeww9VW2+M1YeYFCEL9CWz4ok0B3GSlT83218Uu+iAwdsuqUzqtj214lEa2oO2tBVS7HZSV/syF/HfdyjXi5/hWvAm9KCXmUM3ImWA3RRLotx7cIs2Nw6D9pp65OH2hMzF1BpTFKwVe1gTKKccLKbqdMfq7YJ+YdtJTlGINMmwIDAQABAoGAFcIG9owLVoZbr8ao0v/aLXCY+H8dmgd7Jro3LFbNYcKngc8jJZDugtbV6EiBNfD4FR8q/DSl5K2vuL+jL5Al8Tmb2OMFTS/EY2jJLI7WTWwUWnXSHtqbKpJRQx/HaNgaMX7wXZ5Frpc/QP/5NlNPDeHNpCeQFAnupxOVIzRSXIECQQDPMASXA1dbBBlEoWilrkyLfyXTzyXWG8P0jwkg0EQb442kKrtp6/AejjQy12anstckuyyRdYRXcwwuJAlwmDaRAkEAxlKEh9Qj3j9yBhTwLottJCTTyoJvWXnH/v1U2u/Fj7TheQ8B1Uhw+F/qrdh66dXSwoBpi5bRRzVfm/0rJUeeawJAVwrYUs0/jOhK6U9aVIjGdbCEJtkXDz149KyG7Dcy9fiCkB63v8c0iNG7UkS2RuvWgQL1tWKGp+qYimXvZVM9UQJAOyLj7fSt9VmJ0JJxxA9DLiHlHV+jgFS19CzqHpacnGtdSFHXRBfjx8wiGFCS5iMiQ2kzD7KbGNarecIWGXmvnQJACRWphWy7Z2oO6JdUPCzuK+OJ39zp6TletU/6TssSwI/ZSrcf74s8ooTOf9jnDitJqbfWIGw5g8dDFUlG75/EUw==";

        //测试商户apps_id
        public static String APPS_ID = "5d0006abd0414412b6d994cbd7dcc85d";

        //测试商户mer_id
        public static String MER_ID = "17072512021831085";

        //异步回调URL 此地址必须外网可访问
        public static String NOTIFY_URL = "http://xxx/Mustpay_Notify.aspx";

        //同步回调URL 此地址必须外网可访问
        public static String RETURN_URL = "http://xxx/Mustpay_Return.aspx";

        // 签名方式
        public static String SIGN_TYPE = "RSA";

        // 字符编码格式 目前支持utf-8
        public static String INPUT_CHARSET = "utf-8";

        //下单地址
        public static String ADD_ORDER_URL = "https://service.chinaxiangqiu.com/service/order/saveOrder";

        //订单查询地址
        public static String QUERY_ORDER_URL = "https://service.chinaxiangqiu.com/service/order/queryOrder";


        //↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
    }
}