import com.shb.commons.util.HttpClients;
import com.shb.commons.util.RSAUtils;
import net.sf.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

/**
 * @author xiaoshu
 * @date 2018-05-18 上午10:52
 * @desc
 **/
public class ApiNotifyTest {

    public static void main(String[] args) throws Exception {
        String bfbNotify ="{\"message\":{\"code\":200,\"content\":\"成功\"},\"context\":\"fpnN1Vf+GclbTo6JmapsfboO5eq0ipDIu0pCT7sTYu1kNKZgoPdXJM7ju7/2vNJkAmCPY9dMGNcM5/J2IkDbwAl4v8LnSK0mGmVPtvz25mVcmSdtKoMGnmi8Y7EMuti5H9UbPU6p+KGeVfjAejrbNxbtta9yUbAhpIRLykAHeWvCCkqHRvaHHvNzP6nU7xSX957D2YT62Xr3JBUGg42dh/+O5S9eX6vUWdoadjCNUPupIh+tKqH4w3rES6wjsHXrDCia42h+x/PUXwZ08wD6HovaCGLB7nEa21Z9DfGCYIP3PjKM+nt8DRdW9/AVeOj13dJE3okELpAgU7JRAdM4gJmzB8/ihMroJQOct7FsHKP6drkil+qWxOHvdG2pESZXYshTMkrb30GIGYJtQZHc2S4qfPoE93idkNGuxwhLrGUdBJgX4Q13pF8TRju3PQpO78X9mjQyNdXJpM31ml/e6PkZI4lEsNczRuVHveOAWjPxLOVQ8EOA15OWi9YznLH4iiTKiLDkNBbwMxgsCvqxlD+fx4ixEp/xpB0/y63B/fEqVDL27Wsa8F2nnpIwgifgVpA4VktBtNttpMScbsQf4zTljbSydTbn6sS4t9FHPAAWIaJZAlXdrr/JoSH3AVCoAZXKls3PMWtEji7L5a5DgW9p2koe1k6M2UcNs+hEnhwPQZrXwlt9P4m120LGANMdrun4fPk0VrqoI5j6fN9jZiQAf2ZOQQgwQ+iiJD994w8akiVMhTFFQEcA10dZLhnek0CXacgMawM3v3TF3OdJM2rWt2EkIEnjlhT4KwXAjPVl51pu19Di5mkyM+Bq6kgN0cugC8ZPpvLtmWWKID9Q/l/EUxKh4lf53xq2SzqOwZZEaK3Xf3ZSfKXslP4ajYJK8FFmroliFl3G9grp59Dwm7VEKjWkR17V+nzUPzNvDhOpGUPimX7sHkZhVwu50iFm3ic4AFoUxtNM2+5ROyD7IO0HjeI9omhmMVGu3D1XjVFDmADoZs2S8l+aXt4gX53D\",\"success\":true}";
        bfbNotify(bfbNotify);
    }

    /**
     * 订单查询
     *
     * @throws Exception
     */
    public static String bfbNotify(String bfbNotify) throws Exception {

        String memberPriKeyOther = "MIICXAIBAAKBgQDDQakcqdInHJ1Q2u0Pi2qBYU0WxTWG24Kga3uQ78QNIiXtXy8BdNid4exr9hXQW34byNc5nor/HRUn31hh8PNvVd8y4B6WJDfYKY+Bq5+rSInhi0O1o0Ht2myjYi9rV9/oVdzfOIdF3MqgKEvrNxhsHyuJ9dteHQoGXtWSRnEIDQIDAQABAoGADlxB583FmwLLvyqazM3gI2vYk5gle6mhTdMZ32sC7ERarb6WYnEJjXMURExxBkX0XG7FBYPXjTPCXpBam7lw7dpgR9BhFm09+FLqPlirr64HQlAwQwDyFmQJuGPq5ASzl7e+fIM8qAqWEH6HuEtFSljmebHo2+6OwLxzNcivGTECQQDrq2AQf1moG4Fs1aNNNvETNL5b8doCIjEaZV26V0bNdHKemxjPbhuxENx6bqnIAEaDl5OrajXOgI3WPz8+M2cTAkEA1BnJ2mizVP/Jn+jArwgLfCJYHR/5u589zkGsLly2ugdf3nFZi6pOHWE460AbPzWXXRMUpoJEl+bF6DEUk/wYXwJAb+y7OfqRhRJTHHI2FVTTl5CEG7y4Ei1U7rlXk0kh+i+kxAja9qDPi/97BraJ8c+XraWOX2mY1lMdibQOACd/ewJBAJNCrHEmDIzRY23RLibYUREIz2C5WKy5rTHNSvyNhpi2kgtha6iav82KOPis8735OXR30PiirXlB0tqZaQ4uE8UCQFUlhs1Av7nZAlPOWxOwUxPyYqebWKoi0FFhvYqrd49BHth8bcA1dFJXu0dAIHYnWbxKDBcoERvt61si4ALG+V4=";
        String memberPriKeyJava = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAMNBqRyp0iccnVDa7Q+LaoFhTRbFNYbbgqBre5DvxA0iJe1fLwF02J3h7Gv2FdBbfhvI1zmeiv8dFSffWGHw829V3zLgHpYkN9gpj4Grn6tIieGLQ7WjQe3abKNiL2tX3+hV3N84h0XcyqAoS+s3GGwfK4n1214dCgZe1ZJGcQgNAgMBAAECgYAOXEHnzcWbAsu/KprMzeAja9iTmCV7qaFN0xnfawLsRFqtvpZicQmNcxRETHEGRfRcbsUFg9eNM8JekFqbuXDt2mBH0GEWbT34Uuo+WKuvrgdCUDBDAPIWZAm4Y+rkBLOXt758gzyoCpYQfoe4S0VKWOZ5sejb7o7AvHM1yK8ZMQJBAOurYBB/WagbgWzVo0028RM0vlvx2gIiMRplXbpXRs10cp6bGM9uG7EQ3HpuqcgARoOXk6tqNc6AjdY/Pz4zZxMCQQDUGcnaaLNU/8mf6MCvCAt8IlgdH/m7nz3OQawuXLa6B1/ecVmLqk4dYTjrQBs/NZddExSmgkSX5sXoMRST/BhfAkBv7Ls5+pGFElMccjYVVNOXkIQbvLgSLVTuuVeTSSH6L6TECNr2oM+L/3sGtonxz5etpY5faZjWUx2JtA4AJ397AkEAk0KscSYMjNFjbdEuJthREQjPYLlYrLmtMc1K/I2GmLaSC2FrqJq/zYo4+Kzzvfk5dHfQ+KKteUHS2plpDi4TxQJAVSWGzUC/udkCU85bE7BTE/Jip5tYqiLQUWG9iqt3j0Ee2HxtwDV0Ule7R0AgdidZvEoMFygRG+3rWyLgAsb5Xg==";
        String memberPubKey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDQakcqdInHJ1Q2u0Pi2qBYU0WxTWG24Kga3uQ78QNIiXtXy8BdNid4exr9hXQW34byNc5nor/HRUn31hh8PNvVd8y4B6WJDfYKY+Bq5+rSInhi0O1o0Ht2myjYi9rV9/oVdzfOIdF3MqgKEvrNxhsHyuJ9dteHQoGXtWSRnEIDQIDAQAB";
        String bfbPublicKey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCOAoslcPOFmqk/Okv5sT3z+TsnwjCXtev4OPTM9oLQpr7DwHNYlXIxGkI0rf0RWW6zKMXvrNCYXBjanUYvi0ukM0ujLJiZ+qMutRzxkckqN1ZXSRsjPoCG7S46M1Ew52TKYYkPm/53gqe+gQzdIEDAg8cuxIbSiuKGr2em/jnRfQIDAQAB";

        System.out.println("会员的私钥           :" + memberPriKeyJava);
        System.out.println("会员的公钥           :" + memberPubKey);
        System.out.println("北付宝公钥           :" + bfbPublicKey);

        System.out.println("[速汇宝交易回调]解密前" + bfbNotify);
        String decryptContext = RSAUtils.decryptByPrivateKey(JSONObject.fromObject(bfbNotify).getString("context"), memberPriKeyJava);
        System.out.println("[速汇宝交易回调]解密后" + decryptContext);

        return "SUC";



        }
}
