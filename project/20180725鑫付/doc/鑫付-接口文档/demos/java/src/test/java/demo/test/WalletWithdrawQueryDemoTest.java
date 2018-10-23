package demo.test;

import org.apache.commons.codec.binary.Base64;
import org.apache.commons.lang3.StringUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.testng.annotations.Test;

import demo.RSAUtils;
import demo.WalletWithDrawQueryDemo;
import demo.model.WalletWithDrawQueryReqModel;

/**
 * 提现查询测试
 */
public class WalletWithdrawQueryDemoTest {

    public static Logger logger = LoggerFactory.getLogger(WalletWithdrawQueryDemoTest.class);

    public WalletWithDrawQueryReqModel getTestData() {

        String key = "";
        String src_code = "";

        String out_sn = "12123";
        String biz_sn = "12123";

        return new WalletWithDrawQueryReqModel(src_code, key, out_sn, biz_sn);
    }

    @Test
    public void test() {
        WalletWithDrawQueryDemo demo = new WalletWithDrawQueryDemo();
        WalletWithDrawQueryReqModel reqModel = getTestData();

        String data = demo.query(reqModel);
        System.out.println("data: " + data);

        // 解密
        String ret = null;
        if (StringUtils.isNotEmpty(data)) {
            try {
                String privateKey = TestUtils.getPrivateKey();
                ret = new String(RSAUtils.decryptByPrivateKey(Base64.decodeBase64(data), privateKey));
            } catch (Exception e) {
                // TODO Auto-generated catch block
                e.printStackTrace();
            }

        }
        System.out.println(ret);
    }

}
