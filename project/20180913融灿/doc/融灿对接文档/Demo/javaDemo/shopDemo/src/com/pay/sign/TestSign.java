package com.pay.sign;


public class TestSign {
	public static void main(String[] args) throws Exception {
		String publicKeyStr = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCAYZ5gqFKPOOmiJk+IVstJPfS5DRnGIByjMOL0Waod0v2LHZO7tRubdsWti6JxjNS5Syu0G82YDCyhmEVwy0AE6ufrV7f3IhAQ9AJPkZCA9pCEjDSHtVtt3823A+PFtyQ1Lku+eWqcou+7IwT3uW2a6ZAb9VCcJmVbYFk+xkThdQIDAQAB";
		String privateKeyStr = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAIBhnmCoUo846aImT4hWy0k99LkNGcYgHKMw4vRZqh3S/Ysdk7u1G5t2xa2LonGM1LlLK7QbzZgMLKGYRXDLQATq5+tXt/ciEBD0Ak+RkID2kISMNIe1W23fzbcD48W3JDUuS755apyi77sjBPe5bZrpkBv1UJwmZVtgWT7GROF1AgMBAAECgYA3Jzj+N3H4e6/LbIAAh6Ef3xESqvEmr8b9yNPHu/mchHdOW6+LFZw9psH386Qe+iytSgEFiGhD6P8HkT5L3dWrarrzm7hzWjgVjSRJfk+vR58jqCBXCdCZyCe8QjBeUKIiCdWA3rx9NsO+OZ++2tVMpa9P+c+4yiwUlNK7JXB8BQJBAMbws5q9sW048Y9LLc5EITT1knEKO6HrWRCElz1Kql6W2YemStHGVpIGlYoboPxqqOwyRpszbw+XAapBwPdEHMcCQQClNBbO3LGS8tIPOEa8mshYQ7pLNcw3KBJ5o3ILrWflVjSr7udkMv2KskYJPaPtuAAVUQ1qVnjEYi3N66EfVrvjAkBf7E+tnSmf8IUJAsbjXhZk4sPpnXWDbWdUf5otA4OCeFoK/jO1Ul0LrAEOxqOpEgTBXryMuRAkBDvZTDsu/rihAkEAj9hTI7u2QqV7khUGQqLjXzdZtrMZJc2WiKNwYgqTNHVjV3GluPFNIr8njFRHsG1OZUE11SmF7jkueOZ6XLdA0QJAKgnupYCFvUCtOga6EmHilhZhQwOdgw/cUkmCYK113uuuUKV1JvtHXI/qrGfXZwFWm35kIWfy/EOpidjm+vGlnQ==";
		String plainText = "123112312323";
		
		/* signature */
		String signData = SignUtils.Signaturer(plainText,privateKeyStr);
		System.out.println("original text:"+plainText);
		System.out.println("signData��"+signData);
		
		/* Verify signature  */
		boolean signStat = SignUtils.validataSign(plainText, signData, publicKeyStr);
		System.out.println("Verify signature result:"+signStat);
		
	}
}
