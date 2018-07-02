#ifndef PAYRSA_HTTPRSABUILD_H
#define PAYRSA_HTTPRSABUILD_H

#include <string>
#include <cstring>
#include <map>

using namespace std;

class HttpRsaBuild {
public:
    /**
     * 构造方法
     * @param md5key 秘钥
     * @param public_rsa_key rsa 公钥
     * @param private_rsa_key  rsa 私钥
     */
    HttpRsaBuild(string md5key, string public_rsa_key, string private_rsa_key);

    /**
     * 对 http 请求参数进行加密
     * @param data 请求参数
     * @return
     */
    std::string encrypt(map<std::string, std::string> data);

    /**
     * 对数据进行验签，检查参数是否合法
     * @param data 返回数据
     * @return
     */
    bool checkSign(map<string, string> data);

    /**
     * 生成签名
     * @param data 请求或返回数据
     * @return
     */
    std::string generateSign(map<string, string> data);

protected:
    // 秘钥
    std::string md5key;
    // 公钥
    std::string public_rsa_key;
    // 私钥
    std::string private_rsa_key;
};


#endif //PAYRSA_HTTPRSABUILD_H
