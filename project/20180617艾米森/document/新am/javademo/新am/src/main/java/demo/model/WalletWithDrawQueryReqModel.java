/**
 * 
 */
package demo.model;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

import org.apache.commons.lang3.StringUtils;

public class WalletWithDrawQueryReqModel extends BaseReqModel {

    private String out_sn;
    private String biz_sn;
    private String noncestr = new SimpleDateFormat("yyyyMMddHHmmss").format(new Date()) + "00";

    public WalletWithDrawQueryReqModel(String src_code, String key, String out_sn, String biz_sn) {
        super(src_code, key);
        this.out_sn = out_sn;
        this.biz_sn = biz_sn;
    }

    public Map<String, String> toReqMap() {
        Map<String, String> paramMap = new HashMap<String, String>();

        if (StringUtils.isNotEmpty(this.out_sn)) {
            paramMap.put("out_sn", this.out_sn);
        }
        if (StringUtils.isNotEmpty(this.biz_sn)) {
            paramMap.put("biz_sn", this.biz_sn);
        }
        if (StringUtils.isNotEmpty(this.noncestr)) {
            paramMap.put("noncestr", this.noncestr);
        }
        return makeReqParamMapByRsa(paramMap, this.src_code);
    }

}
