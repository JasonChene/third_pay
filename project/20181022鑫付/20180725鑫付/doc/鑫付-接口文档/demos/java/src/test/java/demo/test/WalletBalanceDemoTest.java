package demo.test;

import org.apache.commons.codec.binary.Base64;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.testng.annotations.Test;

import demo.RSAUtils;
import demo.WalletBalanceDemo;
import demo.model.WalletBalanceReqModel;

/**
 * 钱包余额
 */
public class WalletBalanceDemoTest {

    public static Logger logger = LoggerFactory.getLogger(WalletBalanceDemoTest.class);

    public WalletBalanceReqModel getTestData() {

        String key = "";
        String src_code = "";

        return new WalletBalanceReqModel(src_code, key);
    }

    @Test
    public void test() {
        WalletBalanceDemo demo = new WalletBalanceDemo();
        WalletBalanceReqModel reqModel = getTestData();

        String data = demo.query(reqModel);
        System.out.println("data: " + data);

        // 解密
        String ret = null;
        try {
            String privateKey = TestUtils.getPrivateKey();
            ret = new String(RSAUtils.decryptByPrivateKey(Base64.decodeBase64(data), privateKey));
        } catch (Exception e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        System.out.println(ret);
    }

}
