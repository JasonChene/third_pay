package demo;

import java.util.Map;

import org.apache.commons.lang3.StringUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;

import demo.model.CashierPayReqModel;
import demo.model.KuaiJieReqModel;

public class CashierPayDemo extends BaseDemo {

    public static Logger logger = LoggerFactory.getLogger(CashierPayDemo.class);

    public String payv2(CashierPayReqModel reqModel) {
        String url = ServerConfig.reqUrl + ServerConfig.payv2Path;

        Map<String, String> reqMap = reqModel.toReqMap();

        String html = this.reqApi(url, reqMap);
        String signSn = html;
        try {
            JSONObject retjson = null;
            try {
                retjson = JSON.parseObject(html);
            } catch (Exception e) {
                logger.debug("result is not json。");
            }
            if (retjson != null) {
                String respcd = retjson.getString("respcd");
                if (StringUtils.equalsIgnoreCase(respcd, "0000")) {
                    signSn = retjson.getString("data");
                }
            }
        } catch (Exception e) {
            logger.error(e.getMessage(), e);
        }

        return signSn;
    }

    /**
     * @param key
     * 
     */
    public String payv2(KuaiJieReqModel kuaiJieReqModel) {
        String url = ServerConfig.reqUrl + ServerConfig.payv2Path;

        Map<String, String> reqMap = kuaiJieReqModel.toPayReqMap();

        String html = this.reqApi(url, reqMap);
        String ret = html;
        try {
            JSONObject retjson = null;
            try {
                retjson = JSON.parseObject(html);
            } catch (Exception e) {
                logger.debug("result is not json。");
            }
            if (retjson != null) {
                String respcd = retjson.getString("respcd");
                if (StringUtils.equalsIgnoreCase(respcd, "0000")) {
                    ret = retjson.getString("data");
                }
            }
        } catch (Exception e) {
            logger.error(e.getMessage(), e);
        }

        return ret;
    }

}
