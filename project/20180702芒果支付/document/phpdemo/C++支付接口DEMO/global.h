#ifndef PAYRSA_GLOBAL_H
#define PAYRSA_GLOBAL_H

#include <string>

/**
 * 对数字符串进行 base64 加密
 * @param bytes_to_encode
 * @param in_len
 * @return
 */
std::string base64_encode(unsigned char const *bytes_to_encode, unsigned int in_len);

/**
 * 对字符串进行 base64 解密
 * @param encoded_string
 * @return
 */
std::string base64_decode(std::string const &encoded_string);

/**
 * rsa 加密
 * @param strPemFileName 秘钥文件路径
 * @param strData
 * @return
 */
std::string EncodeRSAKeyFile(const std::string &strPemFileName, const std::string &strData);

/**
 * rsa 解密
 * @param strPemFileName 秘钥文件路径
 * @param strData
 * @return
 */
std::string DecodeRSAKeyFile(const std::string &strPemFileName, const std::string &strData);

/**
 * 生成订单号（请自行处理并发）
 * @return
 */
std::string generate_sn();

#endif //PAYRSA_GLOBAL_H
