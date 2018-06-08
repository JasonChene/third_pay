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

import java.io.UnsupportedEncodingException;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;


public class PaySign {
    private static final String TAG = PaySign.class.getSimpleName();

    public static String md5(String str) {
        //确定计算方法
        MessageDigest md5;
        try {
            md5 = MessageDigest.getInstance("MD5");
        } catch (NoSuchAlgorithmException e) {
            return null;
        }
        byte[] data;
        try {
            data = str.getBytes("utf-8");
        } catch (UnsupportedEncodingException e) {
            return null;
        }
        byte[] md5Date = md5.digest(data);
        return ByteArrayUtil.byteArray2HexString(md5Date);
    }
}
