package demo.test;

import org.apache.commons.codec.binary.Base64;
import org.apache.commons.lang3.StringUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.testng.annotations.Test;

import demo.RSAUtils;
import demo.WalletWithDrawDemo;
import demo.model.WalletWithDrawReqModel;

/**
 * 提现测试
 */
public class WalletWithdrawDemoTest {

    public static Logger logger = LoggerFactory.getLogger(WalletWithdrawDemoTest.class);

    public WalletWithDrawReqModel getTestData() {

        String key = "";
        String src_code = "";

        String out_sn = "12123";
        String head_bank_name = "招商银行";
        String bank_name = "2323232323";
        String account_name = "test";
        String bank_type = "对私";
        String card_type = "储蓄卡";
        String account_no = "2323232323";
        String amt = "1101"; // 单位是分

        return new WalletWithDrawReqModel(src_code, key, out_sn, head_bank_name, bank_name, account_name, bank_type, card_type, account_no, amt);
    }

    @Test
    public void test() {
        WalletWithDrawDemo demo = new WalletWithDrawDemo();
        WalletWithDrawReqModel reqModel = getTestData();

        String data = demo.withdraw(reqModel);
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
