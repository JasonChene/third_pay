import com.shb.commons.util.HttpClients;
import com.shb.commons.util.RSAUtils;
import net.sf.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

/**
 * @author xiaoshu
 * @date 2018-05-16 下午10:16
 * @desc
 **/
public class ApiQueryTest {


    public static void main(String[] args) throws Exception {
        apiTradeQuery();
    }

    /**
     * 订单查询
     *
     * @throws Exception
     */
    public static void apiTradeQuery() throws Exception {
        String memberPriKeyOther = "MIICXAIBAAKBgQDDQakcqdInHJ1Q2u0Pi2qBYU0WxTWG24Kga3uQ78QNIiXtXy8BdNid4exr9hXQW34byNc5nor/HRUn31hh8PNvVd8y4B6WJDfYKY+Bq5+rSInhi0O1o0Ht2myjYi9rV9/oVdzfOIdF3MqgKEvrNxhsHyuJ9dteHQoGXtWSRnEIDQIDAQABAoGADlxB583FmwLLvyqazM3gI2vYk5gle6mhTdMZ32sC7ERarb6WYnEJjXMURExxBkX0XG7FBYPXjTPCXpBam7lw7dpgR9BhFm09+FLqPlirr64HQlAwQwDyFmQJuGPq5ASzl7e+fIM8qAqWEH6HuEtFSljmebHo2+6OwLxzNcivGTECQQDrq2AQf1moG4Fs1aNNNvETNL5b8doCIjEaZV26V0bNdHKemxjPbhuxENx6bqnIAEaDl5OrajXOgI3WPz8+M2cTAkEA1BnJ2mizVP/Jn+jArwgLfCJYHR/5u589zkGsLly2ugdf3nFZi6pOHWE460AbPzWXXRMUpoJEl+bF6DEUk/wYXwJAb+y7OfqRhRJTHHI2FVTTl5CEG7y4Ei1U7rlXk0kh+i+kxAja9qDPi/97BraJ8c+XraWOX2mY1lMdibQOACd/ewJBAJNCrHEmDIzRY23RLibYUREIz2C5WKy5rTHNSvyNhpi2kgtha6iav82KOPis8735OXR30PiirXlB0tqZaQ4uE8UCQFUlhs1Av7nZAlPOWxOwUxPyYqebWKoi0FFhvYqrd49BHth8bcA1dFJXu0dAIHYnWbxKDBcoERvt61si4ALG+V4=";
        String memberPriKeyJava  = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAMNBqRyp0iccnVDa7Q+LaoFhTRbFNYbbgqBre5DvxA0iJe1fLwF02J3h7Gv2FdBbfhvI1zmeiv8dFSffWGHw829V3zLgHpYkN9gpj4Grn6tIieGLQ7WjQe3abKNiL2tX3+hV3N84h0XcyqAoS+s3GGwfK4n1214dCgZe1ZJGcQgNAgMBAAECgYAOXEHnzcWbAsu/KprMzeAja9iTmCV7qaFN0xnfawLsRFqtvpZicQmNcxRETHEGRfRcbsUFg9eNM8JekFqbuXDt2mBH0GEWbT34Uuo+WKuvrgdCUDBDAPIWZAm4Y+rkBLOXt758gzyoCpYQfoe4S0VKWOZ5sejb7o7AvHM1yK8ZMQJBAOurYBB/WagbgWzVo0028RM0vlvx2gIiMRplXbpXRs10cp6bGM9uG7EQ3HpuqcgARoOXk6tqNc6AjdY/Pz4zZxMCQQDUGcnaaLNU/8mf6MCvCAt8IlgdH/m7nz3OQawuXLa6B1/ecVmLqk4dYTjrQBs/NZddExSmgkSX5sXoMRST/BhfAkBv7Ls5+pGFElMccjYVVNOXkIQbvLgSLVTuuVeTSSH6L6TECNr2oM+L/3sGtonxz5etpY5faZjWUx2JtA4AJ397AkEAk0KscSYMjNFjbdEuJthREQjPYLlYrLmtMc1K/I2GmLaSC2FrqJq/zYo4+Kzzvfk5dHfQ+KKteUHS2plpDi4TxQJAVSWGzUC/udkCU85bE7BTE/Jip5tYqiLQUWG9iqt3j0Ee2HxtwDV0Ule7R0AgdidZvEoMFygRG+3rWyLgAsb5Xg==";
        String memberPubKey      = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDQakcqdInHJ1Q2u0Pi2qBYU0WxTWG24Kga3uQ78QNIiXtXy8BdNid4exr9hXQW34byNc5nor/HRUn31hh8PNvVd8y4B6WJDfYKY+Bq5+rSInhi0O1o0Ht2myjYi9rV9/oVdzfOIdF3MqgKEvrNxhsHyuJ9dteHQoGXtWSRnEIDQIDAQAB";

        String bfbPublicKey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCOAoslcPOFmqk/Okv5sT3z+TsnwjCXtev4OPTM9oLQpr7DwHNYlXIxGkI0rf0RWW6zKMXvrNCYXBjanUYvi0ukM0ujLJiZ+qMutRzxkckqN1ZXSRsjPoCG7S46M1Ew52TKYYkPm/53gqe+gQzdIEDAg8cuxIbSiuKGr2em/jnRfQIDAQAB";

        System.out.println("会员的私钥           :"+memberPriKeyJava);
        System.out.println("会员的公钥           :"+memberPubKey);
        System.out.println("北付宝公钥           :"+bfbPublicKey);

        String url = "http://39.108.134.13:10017/api/query/tradeOrder";
        String memberNumber = "LXB00000000000001";
        String lxbOrderNumber ="BFB01180518104914020297000014";

        Map<String, String> mapHead = new HashMap<>();
        mapHead.put("memberNumber", memberNumber);
        mapHead.put("charset", "utf-8");
        mapHead.put("method", "queryTradeOrder");
        mapHead.put("sign", "x");
        mapHead.put("signType", "RSA");
        mapHead.put("version", "v1.0");
        mapHead.put("requestTime", "20180122091213");

        Map<String, String> mapContent = new HashMap<>();
        mapContent.put("lxbOrderNumber", lxbOrderNumber);
        JSONObject businessContext = JSONObject.fromObject(mapContent);
        JSONObject businessHead = JSONObject.fromObject(mapHead);

        System.out.println("发送的报文businessContext:" + businessContext.toString());
        System.out.println("发送的报文businessHead   :" + businessHead.toString());

        String context = RSAUtils.verifyAndEncryptionToString(businessContext, businessHead, memberPriKeyJava, bfbPublicKey);
        JSONObject jsonObject = new JSONObject();
        jsonObject.put("context", context);
        System.out.println("发送的密文context        :" + jsonObject.toString());

        JSONObject returnStr = HttpClients.doPost(url, jsonObject);
        System.out.println("接收的密文               ：" + returnStr);
        System.out.println("接收的报文               ：" + RSAUtils.decryptByPrivateKey(returnStr.getString("context"), memberPriKeyJava));
    }
}
