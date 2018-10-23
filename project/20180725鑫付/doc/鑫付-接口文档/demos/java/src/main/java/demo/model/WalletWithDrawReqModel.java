/**
 * 
 */
package demo.model;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

import org.apache.commons.lang3.StringUtils;

public class WalletWithDrawReqModel extends BaseReqModel {

    private String out_sn;
    private String head_bank_name;
    private String bank_name;
    private String account_name;
    private String bank_type;
    private String card_type;
    private String account_no;
    private String amt;
    private String noncestr = new SimpleDateFormat("yyyyMMddHHmmss").format(new Date()) + "00";

    /**
     * @param src_code
     * @param key
     * @param out_sn
     * @param head_bank_name
     * @param bank_name
     * @param account_name
     * @param bank_type
     * @param card_type
     * @param account_no
     * @param amt
     */
    public WalletWithDrawReqModel(String src_code, String key, String out_sn, String head_bank_name, String bank_name, String account_name, String bank_type, String card_type,
            String account_no, String amt) {
        super(src_code, key);
        this.out_sn = out_sn;
        this.head_bank_name = head_bank_name;
        this.bank_name = bank_name;
        this.account_name = account_name;
        this.bank_type = bank_type;
        this.card_type = card_type;
        this.account_no = account_no;
        this.amt = amt;
    }

    public Map<String, String> toReqMap() {
        Map<String, String> paramMap = new HashMap<String, String>();

        if (StringUtils.isNotEmpty(this.head_bank_name)) {
            paramMap.put("head_bank_name", this.head_bank_name);
        }
        if (StringUtils.isNotEmpty(this.bank_name)) {
            paramMap.put("bank_name", this.bank_name);
        }
        if (StringUtils.isNotEmpty(this.out_sn)) {
            paramMap.put("out_sn", this.out_sn);
        }
        if (StringUtils.isNotEmpty(this.account_name)) {
            paramMap.put("account_name", this.account_name);
        }
        if (StringUtils.isNotEmpty(this.bank_type)) {
            paramMap.put("bank_type", this.bank_type);
        }
        if (StringUtils.isNotEmpty(this.card_type)) {
            paramMap.put("card_type", this.card_type);
        }
        if (StringUtils.isNotEmpty(this.account_no)) {
            paramMap.put("account_no", this.account_no);
        }
        if (StringUtils.isNotEmpty(this.amt)) {
            paramMap.put("amt", this.amt);
        }
        if (StringUtils.isNotEmpty(this.noncestr)) {
            paramMap.put("noncestr", this.noncestr);
        }
        return makeReqParamMapByRsa(paramMap, this.src_code);
    }

}
