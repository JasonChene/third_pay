/**
 * 
 */
package demo.model;

import java.util.HashMap;
import java.util.Map;

import org.apache.commons.lang3.StringUtils;

public class CashierPayReqModel extends BaseReqModel {

    private String mch_id;
    private String total_fee;
    private String goods_name;
    private String out_trade_no;
    private String time_start;
    private String finish_url;

    private String trade_type = "80101";

    /**
     * @param trade_type
     *            the trade_type to set
     */
    public void setTrade_type(String trade_type) {
        this.trade_type = trade_type;
    }

    public CashierPayReqModel(String src_code, String key, String mch_id, String total_fee, String goods_name, String out_trade_no, String time_start, String finish_url) {
        super(src_code, key);
        this.mch_id = mch_id;
        this.total_fee = total_fee;
        this.goods_name = goods_name;
        this.out_trade_no = out_trade_no;
        this.time_start = time_start;
        this.finish_url = finish_url;
    }

    public Map<String, String> toReqMap() {
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

        makeReqParamMap(paramMap);
        return paramMap;
    }

}
