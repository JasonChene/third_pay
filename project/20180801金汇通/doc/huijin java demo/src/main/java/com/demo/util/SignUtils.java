package com.demo.util;


import java.lang.reflect.Field;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.Map;
import java.util.logging.Logger;

/**
 * Created by admin on 2018-03-30 0030.
 */
public class SignUtils {

    private static final Logger logger = Logger.getLogger(SignUtils.class.getName());

    /**
     * 定义签名，微信根据参数字段的ASCII码值进行排序(升序) 加密签名,故使用 SortMap 进行参数排序
     * aa=xx&bb=xxx  最后加上 &appkey=xxx  带有IgnoreSign注解的字段会被忽略
     * @param appkey
     * @param obj
     * @return
     */
    public static String createSign(String appkey, Object obj){
        List<String> ignoreFieldList = getIgnoreFields(obj);

        List<String> params = new ArrayList<>();
        Map<String, String> valueMap = BeanUtil.getObjectFieldVal(obj, true);
        for (Map.Entry<String, String> entry : valueMap.entrySet()) {
            if (ignoreFieldList.contains(entry.getKey())) {//忽略字段不参与签名
                continue;
            }
            params.add(entry.getKey() + "=" + entry.getValue());
        }

        if (params.isEmpty()) {
            throw new RuntimeException("签名内容为空");
        }

        params.add("appkey=" + appkey);
        String content = org.apache.commons.lang3.StringUtils.join(params, "&");
        logger.info("原始签名字符串:" + content);
        return MD5Utils.MD5Encode(content).toUpperCase();
    }

    public static boolean checkSign(String appkey, Object obj, String sign) {
        String s = createSign(appkey, obj);
        return s.toUpperCase().equals(sign.toUpperCase());
    }

    private static List<String> getIgnoreFields(Object obj) {
        List<String> list = new ArrayList<>();

        List<Field> fieldList = new ArrayList<>() ;
        Class tempClass = obj.getClass();
        while (tempClass != null) {//当父类为null的时候说明到达了最上层的父类(Object类).
            fieldList.addAll(Arrays.asList(tempClass.getDeclaredFields()));
            tempClass = tempClass.getSuperclass(); //得到父类,然后赋给自己
        }
        for (Field field : fieldList) {
            IgnoreSign an = field.getAnnotation(IgnoreSign.class);
            if (an != null) {
                list.add(field.getName());
            }
        }
        return list;
    }

    public static void main(String[] args) {
        Tm t = new Tm();
        t.setAddr("深圳");
        t.setAge(18);
        t.setName("张三");
        t.setRemark("");

        System.out.println(SignUtils.createSign("ABC", t));

    }


    static class Tm {
        private String name;
        private Integer age;
        private String addr;
        private String remark;

        public String getName() {
            return name;
        }

        public void setName(String name) {
            this.name = name;
        }

        public Integer getAge() {
            return age;
        }

        public void setAge(Integer age) {
            this.age = age;
        }

        public String getAddr() {
            return addr;
        }

        public void setAddr(String addr) {
            this.addr = addr;
        }

        public String getRemark() {
            return remark;
        }

        public void setRemark(String remark) {
            this.remark = remark;
        }
    }

}
