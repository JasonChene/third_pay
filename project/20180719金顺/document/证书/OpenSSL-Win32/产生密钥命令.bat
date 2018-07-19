openssl genrsa -des3 -out merchant_private.key 1024
openssl pkcs8 -topk8 -inform PEM -outform PEM -nocrypt -in merchant_private.key -out merchant_private_key_pkcs8.pem
openssl rsa -in merchant_private.key -pubout -out merchant_public_key.pem
pause