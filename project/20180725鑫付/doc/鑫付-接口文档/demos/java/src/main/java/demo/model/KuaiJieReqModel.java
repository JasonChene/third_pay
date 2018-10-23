/**
 * 
 */
package demo.model;

import java.util.HashMap;
import java.util.Map;

import org.apache.commons.lang3.StringUtils;

import com.alibaba.fastjson.JSONObject;

public class KuaiJieReqModel extends BaseReqModel {

    private String mch_id;
    private String total_fee;
    private String bankName;
    private String cardType;
    private String accoutNo;
    private String accountName;
    private String idType;
    private String idNumber;
    private String mobile;

    private String goods_name;
    private String out_trade_no;
    private String time_start;
    private String finish_url;

    private String trade_type = "70103";
    private String code;
    private String signSn;

    /**
     * @param code
     *            the code to set
     */
    public void setCode(String code) {
        this.code = code;
    }

    /**
     * @param signSn
     *            the signSn to set
     */
    public void setSignSn(String signSn) {
        this.signSn = signSn;
    }

    public KuaiJieReqModel(String src_code, String key, String mch_id, String total_fee, String bankName, String cardType, String accoutNo, String accountName, String idType,
            String idNumber, String mobile, String goods_name, String out_trade_no, String time_start, String finish_url) {
        super(src_code, key);
        this.mch_id = mch_id;
        this.total_fee = total_fee;
        this.bankName = bankName;
        this.cardType = cardType;
        this.accoutNo = accoutNo;
        this.accountName = accountName;
        this.idType = idType;
        this.idNumber = idNumber;
        this.mobile = mobile;
        this.goods_name = goods_name;
        this.out_trade_no = out_trade_no;
        this.time_start = time_start;
        this.finish_url = finish_url;
    }

    public Map<String, String> toFastSignReqMap() {
        Map<String, String> paramMap = new HashMap<String, String>();

        if (StringUtils.isNotEmpty(this.src_code)) {
            paramMap.put("src_code", this.src_code);
        }
        if (StringUtils.isNotEmpty(this.mch_id)) {
            paramMap.put("mch_id", this.mch_id);
        }
        if (StringUtils.isNotEmpty(this.total_fee)) {
            paramMap.put("total_fee", this.total_fee);
        }
        if (StringUtils.isNotEmpty(this.bankName)) {
            paramMap.put("bankName", this.bankName);
        }
        if (StringUtils.isNotEmpty(this.cardType)) {
            paramMap.put("cardType", this.cardType);
        }
        if (StringUtils.isNotEmpty(this.accoutNo)) {
            paramMap.put("accoutNo", this.accoutNo);
        }
        if (StringUtils.isNotEmpty(this.accountName)) {
            paramMap.put("accountName", this.accountName);
        }
        if (StringUtils.isNotEmpty(this.idType)) {
            paramMap.put("idType", this.idType);
        }
        if (StringUtils.isNotEmpty(this.idNumber)) {
            paramMap.put("idNumber", this.idNumber);
        }
        if (StringUtils.isNotEmpty(this.mobile)) {
            paramMap.put("Mobile", this.mobile);
        }

        makeReqParamMap(paramMap);
        return paramMap;

    }

    public Map<String, String> toPayReqMap() {
        Map<String, String> paramMap = new HashMap<String, String>();

        if (StringUtils.isNotEmpty(this.src_code)) {
            paramMap.put("src_code", this.src_code);
        }
        if (StringUtils.isNotEmpty(this.mch_id)) {
            paramMap.put("mchid", this.mch_id);
        }
        if (StringUtils.isNotEmpty(this.total_fee)) {
            paramMap.put("total_fee", this.total_fee);
        }
        if (StringUtils.isNotEmpty(this.goods_name)) {
            paramMap.put("goods_name", this.goods_name);
        }
        if (StringUtils.isNotEmpty(this.trade_type)) {
            paramMap.put("trade_type", this.trade_type);
        }
        if (StringUtils.isNotEmpty(this.time_start)) {
            paramMap.put("time_start", this.time_start);
        }
        if (StringUtils.isNotEmpty(this.out_trade_no)) {
            paramMap.put("out_trade_no", this.out_trade_no);
        }
        if (StringUtils.isNotEmpty(this.finish_url)) {
            paramMap.put("finish_url", this.finish_url);
        }
        if (StringUtils.isNotEmpty(this.getExtend())) {
            paramMap.put("extend", this.getExtend());
        }

        makeReqParamMap(paramMap);
        return paramMap;
    }

    /**
     * @return
     */
    private String getExtend() {
        Map<String, String> extMap = new HashMap<String, String>();
        extMap.put("accoutNo", this.accoutNo);
        extMap.put("bankName", this.bankName);
        extMap.put("accountName", this.accountName);
        extMap.put("idNumber", this.idNumber);
        extMap.put("Mobile", this.mobile);
        extMap.put("code", this.code);
        extMap.put("signSn", this.signSn);
        extMap.put("idType", this.idType);
        extMap.put("cardType", this.cardType);

        return JSONObject.toJSONString(extMap);
    }

}
