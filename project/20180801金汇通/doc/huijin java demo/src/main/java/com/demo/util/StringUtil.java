package com.demo.util;

import org.apache.commons.lang3.StringUtils;

import java.util.Map;
import java.util.TreeMap;

public class StringUtil extends StringUtils {

    /**
     * 排序
     * @param params
     * @return
     */
    public static String sort(Map<String, String> params,String appKey) {
        Map<String, String> sortMap = new TreeMap<>();
        sortMap.putAll(params);
        // 以k1=v1&k2=v2...方式拼接参数
        StringBuilder builder = new StringBuilder();
        for (Map.Entry<String, String> s : sortMap.entrySet()) {
            String key = s.getKey();
            String value = String.valueOf(s.getValue());
            if (isBlank(value)) {
                continue;
            }
            builder.append(key).append("=").append(value).append("&");
        }
        if (!sortMap.isEmpty()) {
            builder.append("appkey").append("=").append(appKey);
        }
        return builder.toString();

    }
}
