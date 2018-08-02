package com.demo.util;

import java.beans.PropertyDescriptor;
import java.lang.reflect.Field;
import java.util.*;

/**
 * @author
 * @date 2018/3/5.
 */
public class BeanUtil {


    /**
     * 获取实体的所有字段值, 原理是获得实体所有带get方法的字段
     * 结果按key ASCII 排序 (升序)
     * @param obj
     * @param ignoreNullEmpty 是否忽略null 空字符串的字段
     * @return
     */
    public static Map<String, String> getObjectFieldVal(Object obj, boolean ignoreNullEmpty) {
        Map<String, String> valueMap = new  TreeMap<>();
        try {
            List<Field> fieldList = new ArrayList<>() ;
            Class tempClass = obj.getClass();
            while (tempClass != null) {//当父类为null的时候说明到达了最上层的父类(Object类).
                fieldList.addAll(Arrays.asList(tempClass.getDeclaredFields()));
                tempClass = tempClass.getSuperclass(); //得到父类,然后赋给自己
            }
            for (Field field : fieldList) {
                try {
                    PropertyDescriptor pd = new PropertyDescriptor(field.getName(), obj.getClass());
                    Object val = pd.getReadMethod().invoke(obj);
                    if ((val == null || Objects.toString(val, "").trim().equals("")) && ignoreNullEmpty) {
                        continue;
                    }
                    valueMap.put(field.getName(), Objects.toString(val, ""));
                } catch (Exception e1) {
                }

            }
        } catch (Throwable e) {
            throw new RuntimeException(e);
        }
        return valueMap;
    }

    public static void main(String[] args) {

    }

}
