package demo;

import java.util.Map;

import org.apache.commons.lang3.StringUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;

import demo.model.WalletWithDrawReqModel;

public class WalletWithDrawDemo extends BaseDemo {

    public static Logger logger = LoggerFactory.getLogger(WalletWithDrawDemo.class);

    public String withdraw(WalletWithDrawReqModel reqModel) {
        String url = ServerConfig.reqUrl + ServerConfig.withdrawPath;

        Map<String, String> reqMap = reqModel.toReqMap();

        String ret = StringUtils.EMPTY;
        String html = this.reqApi(url, reqMap);
        ret = html;
        try {
            JSONObject retjson = null;
            try {
                retjson = JSON.parseObject(html);
            } catch (Exception e) {
                logger.debug("result is not json。");
            }
            if (retjson != null) {
                String respcd = retjson.getString("respcd");
                String respmsg = retjson.getString("respmsg");
                if (StringUtils.equalsIgnoreCase(respcd, "0000")) {
                    ret = retjson.getString("data");
                } else {
                    ret = respmsg;
                }
            }
        } catch (Exception e) {
            logger.error(e.getMessage(), e);
        }

        return ret;
    }

}
