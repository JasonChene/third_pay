package demo.test;

import java.text.SimpleDateFormat;
import java.util.Date;

import org.apache.commons.lang3.StringUtils;
import org.testng.annotations.Test;

import demo.KuaiJieDemo;
import demo.model.KuaiJieReqModel;

/**
 * 快捷
 */
public class KuaiJieDemoTest {

    public KuaiJieReqModel getTestData() {

        String key = "";
        String src_code = "";
        String mch_id = "";

        String total_fee = "101"; // 单位是分
        String bankName = "招商银行"; // 总行名称，比如：招商银行。工商银行。。。
        String cardType = "借记卡"; // 借记卡或者信用卡
        String accoutNo = ""; // 卡号
        String accountName = ""; // 开户卡姓名
        String idType = "身份证"; // 证件类型，身份证
        String idNumber = ""; // 身份证号
        String mobile = ""; // 手机号

        String goods_name = "XXXX"; // 订单名称
        String out_trade_no = "12121212"; // 订单号
        String time_start = new SimpleDateFormat("yyyyMMddHHmmss").format(new Date());
        String finish_url = "http://www.abc.com"; // 完成页跳转url

        return new KuaiJieReqModel(src_code, key, mch_id, total_fee, bankName, cardType, accoutNo, accountName, idType, idNumber, mobile, goods_name, out_trade_no, time_start,
                finish_url);
    }

    /**
     * 快捷预下单
     */
    @Test
    public void testKuaiJieFastSignDemo() {
        KuaiJieDemo kuaiJieDemo = new KuaiJieDemo();
        String signSn = kuaiJieDemo.fastSign(getTestData());
        System.out.println(signSn);

    }

    /**
     * 快捷支付。依赖预下单
     */
    @Test
    public void testKuaiJiePayDemo() {
        KuaiJieDemo kuaiJieDemo = new KuaiJieDemo();

        // FIXME: 从上一个接口获取而来
        String signSn = "20170612859132411";
        String code = "377055";

        if (StringUtils.isEmpty(code) || StringUtils.isEmpty(signSn)) {
            System.out.println("预下单订单号或者短信验证码为空");
            return;
        }

        KuaiJieReqModel kuaiJieReqModel = getTestData();
        kuaiJieReqModel.setCode(code);
        kuaiJieReqModel.setSignSn(signSn);

        String ret = kuaiJieDemo.payv2(kuaiJieReqModel);
        System.out.println(ret);

    }
}
