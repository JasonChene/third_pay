import java.lang.reflect.Field;

public class ReflectUtil {

    public static String getEncryptionParam(Object obj) throws IllegalAccessException {
        String result = "";
        if(obj != null){
            Field[] fields= obj.getClass().getDeclaredFields();
            for(Field f:fields){
                f.setAccessible(true);
                Object value = f.get(obj);
                if(value != null){
                    result += f.getName() +"="+ value.toString() + "&";
                }
            }
            if(result.length() > 1){
                result = result.substring(0,result.length()-1);
            }
//            System.out.println("待加密字符串:" + result);
            return result;
        }
        System.out.println("加密字符串拼接对象为空");
        return null;
    }
}
