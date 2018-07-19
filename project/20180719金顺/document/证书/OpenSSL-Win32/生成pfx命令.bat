openssl genrsa -des3 -out merchant_private.key 1024
openssl req -new -x509 -key merchant_private.key -days 750 -out merchant_public.cer -config openssl.cfg
openssl pkcs12 -export -name test-alias -in merchant_public.cer -inkey merchant_private.key -out merchant_cert.pfx