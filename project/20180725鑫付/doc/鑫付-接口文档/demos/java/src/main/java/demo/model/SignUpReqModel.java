/**
 * 
 */
package demo.model;

import java.util.HashMap;
import java.util.Map;

import org.apache.commons.lang3.StringUtils;

public class SignUpReqModel extends BaseReqModel {

    private String out_mchid;
    private String mobile;
    private String legalName;
    private String legalId;
    private String mercName;
    private String mainBusi;
    private String provName;
    private String cityName;
    private String countyName;
    private String detailAddr;
    private String accountType;
    private String accountNo;
    private String accountName;
    private String depositBnkName;
    private String depositProvName;
    private String depositCityName;
    private String depositLBnkName;
    private String mchntTag;
    private String bankCode;
    private String alifeeRate;
    private String wxfeeRate;
    private String qqfeeRate;
    private String fastpayRate;
    private String gatewayRate;
    private String feeRate;
    private String WithdrawFee;

    public SignUpReqModel(String src_code, String key, String out_mchid, String mobile, String legalName, String legalId, String mercName, String mainBusi, String provName,
            String cityName, String countyName, String detailAddr, String accountType, String accountNo, String accountName, String depositBnkName, String depositProvName,
            String depositCityName, String depositLBnkName, String mchntTag, String bankCode, String alifeeRate, String wxfeeRate, String qqfeeRate, String fastpayRate,
            String gatewayRate, String feeRate, String WithdrawFee) {
        super(src_code, key);
        this.out_mchid = out_mchid;
        this.mobile = mobile;
        this.legalName = legalName;
        this.legalId = legalId;
        this.mercName = mercName;
        this.mainBusi = mainBusi;
        this.provName = provName;
        this.cityName = cityName;
        this.countyName = countyName;
        this.detailAddr = detailAddr;
        this.accountType = accountType;
        this.accountNo = accountNo;
        this.accountName = accountName;
        this.depositBnkName = depositBnkName;
        this.depositProvName = depositProvName;
        this.depositCityName = depositCityName;
        this.depositLBnkName = depositLBnkName;
        this.mchntTag = mchntTag;
        this.bankCode = bankCode;
        this.alifeeRate = alifeeRate;
        this.wxfeeRate = wxfeeRate;
        this.qqfeeRate = qqfeeRate;
        this.fastpayRate = fastpayRate;
        this.gatewayRate = gatewayRate;
        this.feeRate = feeRate;
        this.WithdrawFee = WithdrawFee;
    }

    public Map<String, String> toReqMap() {
        Map<String, String> paramMap = new HashMap<String, String>();

        if (StringUtils.isNotEmpty(this.src_code)) {
            paramMap.put("src_code", this.src_code);
        }
        if (StringUtils.isNotEmpty(this.out_mchid)) {
            paramMap.put("out_mchid", this.out_mchid);
        }
        if (StringUtils.isNotEmpty(this.mobile)) {
            paramMap.put("mobile", this.mobile);
        }
        if (StringUtils.isNotEmpty(this.legalName)) {
            paramMap.put("legalName", this.legalName);
        }
        if (StringUtils.isNotEmpty(this.legalId)) {
            paramMap.put("legalId", this.legalId);
        }
        if (StringUtils.isNotEmpty(this.mercName)) {
            paramMap.put("mercName", this.mercName);
        }
        if (StringUtils.isNotEmpty(this.mainBusi)) {
            paramMap.put("mainBusi", this.mainBusi);
        }
        if (StringUtils.isNotEmpty(this.provName)) {
            paramMap.put("provName", this.provName);
        }
        if (StringUtils.isNotEmpty(this.cityName)) {
            paramMap.put("cityName", this.cityName);
        }
        if (StringUtils.isNotEmpty(this.countyName)) {
            paramMap.put("countyName", this.countyName);
        }
        if (StringUtils.isNotEmpty(this.detailAddr)) {
            paramMap.put("detailAddr", this.detailAddr);
        }
        if (StringUtils.isNotEmpty(this.accountType)) {
            paramMap.put("accountType", this.accountType);
        }
        if (StringUtils.isNotEmpty(this.accountNo)) {
            paramMap.put("accountNo", this.accountNo);
        }
        if (StringUtils.isNotEmpty(this.accountName)) {
            paramMap.put("accountName", this.accountName);
        }
        if (StringUtils.isNotEmpty(this.depositBnkName)) {
            paramMap.put("depositBnkName", this.depositBnkName);
        }
        if (StringUtils.isNotEmpty(this.depositProvName)) {
            paramMap.put("depositProvName", this.depositProvName);
        }
        if (StringUtils.isNotEmpty(this.depositCityName)) {
            paramMap.put("depositCityName", this.depositCityName);
        }
        if (StringUtils.isNotEmpty(this.depositLBnkName)) {
            paramMap.put("depositLBnkName", this.depositLBnkName);
        }
        if (StringUtils.isNotEmpty(this.mchntTag)) {
            paramMap.put("mchntTag", this.mchntTag);
        }
        if (StringUtils.isNotEmpty(this.bankCode)) {
            paramMap.put("bankCode", this.bankCode);
        }
        if (StringUtils.isNotEmpty(this.alifeeRate)) {
            paramMap.put("alifeeRate", this.alifeeRate);
        }
        if (StringUtils.isNotEmpty(this.wxfeeRate)) {
            paramMap.put("wxfeeRate", this.wxfeeRate);
        }
        if (StringUtils.isNotEmpty(this.qqfeeRate)) {
            paramMap.put("qqfeeRate", this.qqfeeRate);
        }
        if (StringUtils.isNotEmpty(this.fastpayRate)) {
            paramMap.put("fastpayRate", this.fastpayRate);
        }
        if (StringUtils.isNotEmpty(this.gatewayRate)) {
            paramMap.put("gatewayRate", this.gatewayRate);
        }
        if (StringUtils.isNotEmpty(this.feeRate)) {
            paramMap.put("feeRate", this.feeRate);
        }
        if (StringUtils.isNotEmpty(this.WithdrawFee)) {
            paramMap.put("WithdrawFee", this.WithdrawFee);
        }

        makeReqParamMap(paramMap);

        return paramMap;
    }

}
