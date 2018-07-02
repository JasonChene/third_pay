<?php
class Rsa {
	public function encrypt($params, $path)
    {
        $originalData = json_encode($params);
        $crypto = '';
        $encryptData = '';
        $rsaPublicKey = file_get_contents($path);
        foreach (str_split($originalData, 117) as $chunk) {
            openssl_public_encrypt($chunk, $encryptData, $rsaPublicKey);
            $crypto .= $encryptData;
        }
        return base64_encode($crypto);
    }
}