package com.pay.sign;

import java.security.KeyPair;
import java.security.KeyPairGenerator;
import java.security.NoSuchAlgorithmException;
import java.security.SecureRandom;
import java.security.interfaces.RSAPrivateKey;
import java.security.interfaces.RSAPublicKey;

import com.sun.org.apache.xerces.internal.impl.dv.util.Base64;
/**
 * RSA key pair generation
 */
public class KeyGenUtil {
	 /** 
     * Randomly generated key pairs
     */  
    public static void genKeyPair() {  
        // KeyPairGenerator is used to generate the public and private key pairs, generating objects based on the RSA algorithm
        KeyPairGenerator keyPairGen = null;  
        try {  
            keyPairGen = KeyPairGenerator.getInstance("RSA");  
        } catch (NoSuchAlgorithmException e) {  
            // TODO Auto-generated catch block  
            e.printStackTrace();  
        }  
        // Initializes the key pair generator with a key size of 96-1024 bits
        keyPairGen.initialize(1024,new SecureRandom());  
        // Create a key pair and save it in keyPair
        KeyPair keyPair = keyPairGen.generateKeyPair();  
        // Get the private key
        RSAPrivateKey privateKey = (RSAPrivateKey) keyPair.getPrivate();  
        // Get public key  
        RSAPublicKey publicKey = (RSAPublicKey) keyPair.getPublic();  
        try {  
            // Get the public key string
            String publicKeyString = Base64.encode(publicKey.getEncoded());  
            // Get the private key string
            String privateKeyString = Base64.encode(privateKey.getEncoded());  
            System.out.println("publicKeyString:"+publicKeyString);
            System.out.println("privateKeyString:"+privateKeyString);
        } catch (Exception e) {  
            e.printStackTrace();  
        }  
    }  
    
    public static void main(String[] args) {
    	genKeyPair();
    	String signSourceStr = "abcdefg";
    	String privateKey = "MIICdAIBADANBgkqhkiG9w0BAQEFAASCAl4wggJaAgEAAoGBAJVPvXtEHJwnjRMq1XjgoMZsET/4ciHJNR7h3gzRwL7GRwJC8hXO0y+XHAbK4vKvc/0+okEMrScUgHmrB7HzUpFDdMbDRaGVC0UAvcZE0P9g+58CUN0bkNyI0wqxLzhpvz5i10ZEfCYsqh8sZvTpzkomr7mgqtsL9pnEr8FPycQbAgMBAAECgYBAPQJkmjVE6a9EY9VcICiLtcrmHtnbt1lnY/3IviWS7nN2gZ7tywIJI5YnRfrGNr5MYjokinOksKllrzOsV+dZd6VEymZDx743ow09rqWhdjG4hFJ6Tnlb6fw7TelDW44htFgs9WruBdc9J5ziHlfaicVkKd59lDz1obR6F39DwQJBAMz1KP2gEWyeqwoRhQfcGDwE4pYKzL4+QjPWPJaiNhOJoOqdCnQCE58GIwRmxty1eJqnqEPsZhx+qiidCG3cl3kCQQC6fuwcfWBh7rtKjsIzl9Inlgj2CLNGX4viOUxAD7QHiGgreN529LiEE+MuMUPZN9NP0Xcqh4zX/Ap/G/LYU48zAkBR0v5OYv0R1DaMinoFrUSvkXO0WxNqUAi2ES2XJaNZIXTwtUlbDwkuT5DjTPTPYFOJyq1OkK4jah4coLSgx5RhAj8caTmPHYqRYM0njpgHRBm/7htvX+Pv1J562D/Fgp7qht1XwhIiDSYO+PijEN5FOTv37PE6iWvre3od4yQb0J8CQEWvCX/rPgDL/tDNu9s0CoeOlk0EsoUlqIfauh8hfiVxZjLXt6pdfdzM215MowHfsADo6a0nvHc9ZdywO/+263w=";
    	String publicKey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCVT717RBycJ40TKtV44KDGbBE/+HIhyTUe4d4M0cC+xkcCQvIVztMvlxwGyuLyr3P9PqJBDK0nFIB5qwex81KRQ3TGw0WhlQtFAL3GRND/YPufAlDdG5DciNMKsS84ab8+YtdGRHwmLKofLGb06c5KJq+5oKrbC/aZxK/BT8nEGwIDAQAB";
    	String sign = SignUtils.Signaturer(signSourceStr, privateKey);
    	System.out.println(SignUtils.validataSign(signSourceStr, sign, publicKey));
	}
    
}
