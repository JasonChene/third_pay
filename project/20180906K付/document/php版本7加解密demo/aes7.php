<?php
class OpenSSLAES
{
    /**
     * var string $method 加解密方法，可通过openssl_get_cipher_methods()获得
     */
    protected $method;

    /**
     * var string $secret_key 加解密的密钥
     */
    protected $secret_key;

    /**
     * var string $iv 加解密的向量，有些方法需要设置比如CBC
     */
    protected $iv;

    /**
     * var string $options （不知道怎么解释，目前设置为0没什么问题）
     */
    protected $options; 

    /**
     * 构造函数
     *
     * @param string $key 密钥
     * @param string $method 加密方式
     * @param string $iv iv向量
     * @param mixed $options 还不是很清楚 
     *
     */
    public function __construct($key, $method = 'aes-256-ecb', $iv = '', $options =  OPENSSL_RAW_DATA)
    {
        // key是必须要设置的
        $this->secret_key = isset($key) ? $key : exit('key为必须项');

        $this->method = $method;

        $this->iv = $iv;

        $this->options = $options;
    }

    /**
     * 加密方法，对数据进行加密，返回加密后的数据
     *
     * @param string $data 要加密的数据
     * 
     * @return string 
     *
     */
    public function encrypt($data)
    {
        return base64_encode(openssl_encrypt($data, $this->method, $this->secret_key, $this->options ));
    }

    /**
     * 解密方法，对数据进行解密，返回解密后的数据
     *
     * @param string $data 要解密的数据
     * 
     * @return string 
     *
     */
    public function decrypt($data)
    {
        return openssl_decrypt(base64_decode($data), $this->method, $this->secret_key, $this->options);
    }
}

//echo implode('!', openssl_get_cipher_methods());;

$aes = new OpenSSLAES('c53c0a357ac74e6e95004acde339db5e');
$con = '1234567890123adfasdfasd的说法';
$encrypted = $aes->encrypt($con);
// KSGYvH0GOzQULoLouXqPJA==
echo '要加密的字符串：'.$con.'<br>加密后的字符串：', $encrypted, '<hr>';

$decrypted = $aes->decrypt('trHTG9kDC962k3I7Nll2aubBvbzaYef3K/xLkaAsbeT8USPDDTa8V7OmHFaGbtVhJF8H9YI80I3IbiIUgSoRzljFZXGk86W0x/2wyWbOto+SHxF9eJi7PB1zlyOHp88GgFfw8WDmwfR4WM9bwxxX7EMpziXni0oK7o8fXt3N8TF6qhkQIei/MJo2v+OS7eG2Mfm/3P4dd1w0F2S0CJoRKz/9BpOlw5ymQnYjfB31e9TbtSdQCrhHLh+bKRnWQOWTsolA/ZXQUH8VR+l8YLn1vsB3+9ILU1+4otOSRModFGoZYYrrdD5LyY+372ZRv4XCGkeP0zNiigIGFWHt9Cbsug==');

echo '要解密的字符串：', $encrypted, '<br>解密后的字符串：', $decrypted;
?>