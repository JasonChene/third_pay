openssl genrsa -des3 -out merchant_private_key.pem 1024
openssl pkcs8 -topk8 -inform PEM -outform PEM -nocrypt -in merchant_private_key.pem -out merchant_private_key_pkcs8.pem
openssl rsa -in merchant_private_key.pem -pubout -out merchant_public_key.pem
pause