package util;


import java.util.*;



/**
 * Class TLinx2Util
 * Description tlinx api 工具类
 * Create 2017-03-07 14:01:23
 * @author Benny.YEE
 */
public class TLinx2Util {

    // 排序
    public static String sort(Map paramMap) throws Exception {
        String sort = "";
        TLinxMapUtil signMap = new TLinxMapUtil();
        if (paramMap != null) {
            String key;
            for (Iterator it = paramMap.keySet().iterator(); it.hasNext();) {
                key = (String) it.next();
                String value = ((paramMap.get(key) != null) && (!(""
                        .equals(paramMap.get(key).toString())))) ? paramMap
                        .get(key).toString() : "";
                signMap.put(key, value);
            }
            signMap.sort();
            for (Iterator it = signMap.keySet().iterator(); it.hasNext();) {
                key = (String) it.next();
                sort = sort + key + "=" + signMap.get(key).toString() + "&";
            }
            if ((sort != null) && (!("".equals(sort)))) {
                sort = sort.substring(0, sort.length() - 1);
            }
        }
        return sort;
    }
}

