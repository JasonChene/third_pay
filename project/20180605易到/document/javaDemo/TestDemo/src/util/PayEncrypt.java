/*
 * Copyright (c) 2013. Kevin Lee (http://182.92.183.142/).
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

package util;

import java.security.Key;

import javax.crypto.Cipher;
import javax.crypto.SecretKeyFactory;
import javax.crypto.spec.DESedeKeySpec;
import javax.crypto.spec.IvParameterSpec;


public class PayEncrypt {
    private static final String Algorithm = "desede/CBC/PKCS5Padding"; //定义 加密算法,可用 DES,DESede,Blowfish
    // 向量
    private final static String iv = "01234567";
    private static String encoding = "utf-8";

    public PayEncrypt() {
        //do nothing
    }

    /**
     * 3DES加密
     *
     * @param plainText 普通文本
     * @return
     * @throws Exception
     */
    public static String encryptMode(String secretKey, String plainText)
            throws EncException {
        Key deskey = null;
        try {
            DESedeKeySpec spec = new DESedeKeySpec(secretKey.getBytes());
            SecretKeyFactory keyfactory = SecretKeyFactory
                    .getInstance("desede");
            deskey = keyfactory.generateSecret(spec);
            Cipher cipher = Cipher.getInstance(Algorithm);
            IvParameterSpec ips = new IvParameterSpec(iv.getBytes());
            cipher.init(Cipher.ENCRYPT_MODE, deskey, ips);
            byte[] encryptData = cipher.doFinal(plainText.getBytes(encoding));
            return BASE64Util.encryptBASE64(encryptData);
//            return Base64.encode(encryptData);
        } catch (Exception e) {
            throw new EncException(1, "PayEncrypt", "encryptMode", "解密错误", null);
        }
    }

    /**
     * 3DES解密
     *
     * @param encryptText 加密文本
     * @return
     * @throws Exception
     */
    public static String decryptMode(String secretKey, String encryptText)
            throws EncException {
        Key deskey = null;
        try {
            DESedeKeySpec spec = new DESedeKeySpec(secretKey.getBytes());
            SecretKeyFactory keyfactory = SecretKeyFactory
                    .getInstance("desede");
            deskey = keyfactory.generateSecret(spec);
            Cipher cipher = Cipher.getInstance(Algorithm);
            IvParameterSpec ips = new IvParameterSpec(iv.getBytes());
            cipher.init(Cipher.DECRYPT_MODE, deskey, ips);
//            byte[] decryptData = cipher.doFinal(Base64.decode(encryptText));
            byte[] decryptData = cipher.doFinal(BASE64Util.decryptBASE64(encryptText));
            return new String(decryptData, encoding);
        } catch (Exception e) {
//        	Log.d("exception", "解密：", e);
            throw new EncException(1, "PayEncrypt", "encryptMode", "解密错误", null);
        }

    }
}
