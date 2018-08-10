<?php

/////////////////////// get the value of "sign" parameter(RSA-S encrypt)/////////////////////
/*1)merchant_private_key,get it from the tools for getting keys,please refer to the file call <how to get the keys>
  2)you also need to get the merchant_public_key and upload it on Dinpay mechant system,also refer to <how to get the keys>
  3)the merchant_private_key and merchant_public_key are for mechant ID 1118004517,please get yours
*/	
	$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAMXzGf588R76YwwR
ZfUHX/pvRA0+dkP/gC3Yfo1dJmyhrzN1hEsZ8z8jMt2RyLnrSZ7KpjstC4smS9cf
VUq4N4TdFuyKv/LwNTNQvpcX18S7OE8ZZiAtEnNGQ57eQql06//0jggkB4y5mhIb
ngeD16smUtiYY5gwegLqp7sQQZyRAgMBAAECgYEAiEdQawsbeYdKL2G+/s1f/2sg
v7lz9GZvmaCFp88sh/dcRiIuvQGVnK8f8rjOJ2lcGu1LOkxNuTPZXLYeoz1mQmQI
/Dlcw9b3DNwTWuaYNGV+ljJpfnOO3IFXYFlvRZIbBNrIOloE7zHkIyVrdweSmhZG
fuZ0gHkqDrUKEdDpuRECQQD34xJFiSKNwTLfO2E+s91H/B+JwNY2wDPBa4OgQhdh
lhMDw8TfCONU6dda/ATIppEJrTfL180xBvkKYwXgnX+VAkEAzG2g2UNKK52b/I+r
mrLUTW+PE0ae9OmR/Nj1kZSosfZ35Sh8+5zgcrCNfdeTRH2VDHTCCXJ/ytrpPreW
dNkaDQJAdyRJZOh7lhxUohx9KdDzOyT/14q6qsgIWB+fvQfnCv1BmF6gof44nVhj
LJTSi8obDcaWeb/4HGdYjVh4u7OXXQJAWsVi4pXKXUuCc8anf+1f73JVqU12T3FW
7Vq4z4ee0EaMPiiYNnEWCFb0vKf4MDVC9WDyt5crvzsszjheikvMEQJBAMfDUpgR
1rLrbqS/VD5YzN2sx0h1jdJFdag1vTlHTtyRgin5d8r7Eb0G1PiaOIbp/kj4Y/rH
E59D7jBg9IafIVE=
-----END PRIVATE KEY-----';

	//copy the contents between -----BEGIN PUBLIC KEY----- and -----END PUBLIC KEY----,then upload paste the contents on Dinpay mechant system
	$merchant_public_key = '-----BEGIN PUBLIC KEY-----
	MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDF8xn+fPEe+mMMEWX1B1/6b0QNPn
	ZD/4At2H6NXSZsoa8zdYRLGfM/IzLdkci560meyqY7LQuLJkvXH1VKuDeE3Rbsir/y
	8DUzUL6XF9fEuzhPGWYgLRJzRkOe3kKpdOv/9I4IJAeMuZoSG54Hg9erJlLYmGOYMH
	oC6qe7EEGckQIDAQAB
	-----END PUBLIC KEY-----';
	
	
	//dinpay_public_key,copy it form Dinpay merchant system,find it on "Payment Management"->"Public Key Management"->"Dinpay Public Key"
	$dinpay_public_key ='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDZDirdU1syeYUoKJq
t2QoxDHiWE4WNoewR0DBWlqMtQRC0GK9+v9QGG+WDTcIRiJr5tVusJo
4hK/B5YYWlJs7ubrMSqFs7dWPrfplPYZUmR6J667c46tR6aDuD3vmoP
viUXrIgrJRxgYCfl5wETvL8FIH2datclMtJuSba9+73nwIDAQAB 
-----END PUBLIC KEY-----'; 
	



?>