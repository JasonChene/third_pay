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

import org.apache.commons.codec.binary.Base64;





public class BASE64Util {
	private BASE64Util() {
	}

	/**
	 * 解密BASE64
	 *
	 * @param key
	 * @return
	 * @throws Exception
	 */
	public static byte[] decryptBASE64(String key) throws Exception {
//		return Base64.decode(key, Base64.DEFAULT);
		return Base64.decodeBase64(key);
	}

	/**
	 * 加密BASE64
	 *
	 * @param key
	 * @return
	 * @throws Exception
	 */
	public static String encryptBASE64(byte[] key) throws Exception {
//		return Base64.encodeToString(key);
		return Base64.encodeBase64String(key);
	}

}
