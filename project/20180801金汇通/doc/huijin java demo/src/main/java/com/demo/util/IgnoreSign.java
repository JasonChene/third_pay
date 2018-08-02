package com.demo.util;

import java.lang.annotation.*;

/**
 * Created by admin on 2017-08-29 0029.
 */
@Target(ElementType.FIELD)// 注解会在class字节码文件中存在，在运行时可以通过反射获取到
@Retention(RetentionPolicy.RUNTIME)//定义注解的作用目标**作用范围字段、枚举的常量/方法
@Documented//说明该注解将被包含在javadoc中
public @interface IgnoreSign {
}
