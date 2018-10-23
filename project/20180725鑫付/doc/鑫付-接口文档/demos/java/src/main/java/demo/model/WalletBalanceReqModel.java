/**
 * 
 */
package demo.model;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

import org.apache.commons.lang3.StringUtils;

public class WalletBalanceReqModel extends BaseReqModel {

    private String noncestr = new SimpleDateFormat("yyyyMMddHHmmss").format(new Date()) + "00";

    public WalletBalanceReqModel(String src_code, String key) {
        super(src_code, key);
    }

    public Map<String, String> toReqMap() {
        Map<String, String> paramMap = new HashMap<String, String>();

        if (StringUtils.isNotEmpty(this.noncestr)) {
            paramMap.put("noncestr", this.noncestr);
        }
        return makeReqParamMapByRsa(paramMap, this.src_code);
    }

}
