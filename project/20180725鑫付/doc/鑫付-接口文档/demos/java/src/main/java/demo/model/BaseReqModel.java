/**
 * 
 */
package demo.model;

import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.apache.commons.codec.binary.Base64;
import org.apache.commons.codec.digest.DigestUtils;
import org.apache.commons.lang3.StringUtils;

import demo.RSAUtils;

public class BaseReqModel {

    protected String src_code;

    protected String key;

    /**
     * @param src_code
     * @param key
     */
    public BaseReqModel(String src_code, String key) {
        super();
        this.src_code = src_code;
        this.key = key;
    }

    public void makeReqParamMap(Map<String, String> paramMap) {
        List<String> params = new ArrayList<String>();
        List<String> sortedkeys = new ArrayList<String>(paramMap.keySet());
        Collections.sort(sortedkeys);
        for (String rk : sortedkeys) {
            params.add(rk + "=" + paramMap.get(rk));
        }
        String presign = StringUtils.join(params, "&") + "&key=" + this.key;
        System.out.println("签名字符串:" + presign);
        String sign = StringUtils.upperCase(DigestUtils.md5Hex(presign));
        System.out.println("签名:" + sign);
        paramMap.put("sign", sign);

    }

    public Map<String, String> makeReqParamMapByRsa(Map<String, String> paramMap, String src_code) {
        List<String> params = new ArrayList<String>();
        List<String> sortedkeys = new ArrayList<String>(paramMap.keySet());
        Collections.sort(sortedkeys);
        for (String rk : sortedkeys) {
            params.add(rk + "=" + paramMap.get(rk));
        }
        String presign = StringUtils.join(params, "&") + "&key=" + this.key;
        System.out.println("签名字符串:" + presign);
        String sign = StringUtils.upperCase(DigestUtils.md5Hex(presign));
        System.out.println("签名:" + sign);
        String encryptdata = StringUtils.join(params, "&") + "&sign=" + sign;
        System.out.println("RSA前数据:" + encryptdata);

        String publicKey = getPublicKey();
        try {
            encryptdata = Base64.encodeBase64String(RSAUtils.encryptByPublicKey(encryptdata.getBytes(), publicKey));
        } catch (Exception e) {
            e.printStackTrace();
        }
        System.out.println("RSA后数据:" + encryptdata);
        Map<String, String> newparamMap = new HashMap<String, String>();
        newparamMap.put("encrypt_data", encryptdata);
        newparamMap.put("src_code", src_code);
        return newparamMap;
    }

    public String getPublicKey() {
        StringBuffer sb = new StringBuffer();
        String split = "";
        // sb.append("-----BEGIN PUBLIC KEY-----").append(split);
        sb.append("MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCb1t8zSSGChPJA2kl96TzLm6Dw").append(split);
        sb.append("HuFuxFC+TAkzWXINudn4I0jnb0pVL66zjxhsZVM1BDUyv0FOwytdthY6wovIIEd/").append(split);
        sb.append("wZoet2bwC5mujW9ltTlVojU/PYPf2AOkP9/Y/FcPMLtJK65mnwvW+7uXTOpv9B/a").append(split);
        sb.append("emzUHcY5GW5sihcQVwIDAQAB").append(split);
        // sb.append("-----END PUBLIC KEY-----");

        return sb.toString();
    }

}
