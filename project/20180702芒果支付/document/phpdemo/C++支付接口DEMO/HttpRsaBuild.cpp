#include <string>

#include <rapidjson/document.h>
#include <rapidjson/writer.h>
#include <rapidjson/stringbuffer.h>

#include "HttpRsaBuild.h"
#include "md5.h"
#include "global.h"

using namespace rapidjson;

HttpRsaBuild::HttpRsaBuild(string md5key, string public_rsa_key, string private_rsa_key) {

    this->md5key = md5key;
    this->public_rsa_key = public_rsa_key;
    this->private_rsa_key = private_rsa_key;
}

bool HttpRsaBuild::checkSign(map<string, string> data) {

    return data["sign"] == this->generateSign(data);
}

std::string HttpRsaBuild::generateSign(map<string, string> data) {

    // 删除 sign, code, msg
    data.erase("sign");
    data.erase("code");
    data.erase("msg");
    // 待进行 md5 签名的字串
    string md5string = "";
    // 开始遍历 map，并进行数据拼接
    map<string, string>::iterator it;
    it = data.begin();
    while (it != data.end()) {
        md5string.append(it->first.c_str());
        md5string.append("=");
        md5string.append(it->second.c_str());
        md5string.append("&");
        it++;
    }
    // 待签名字串的 key
    md5string.append("key");
    md5string.append("=");
    md5string.append(this->md5key);
    // 进行 md5 签名
    MD5 md5(md5string);
    string sign = md5.md5();

    return sign;
}

std::string HttpRsaBuild::encrypt(map<string, string> data) {

    // 待进行 md5 签名的字串
    string md5string = "";
    // json 字串
    StringBuffer json;
    Writer<StringBuffer> writer(json);
    writer.StartObject();
    // 开始遍历 map，并进行数据拼接
    map<string, string>::iterator it;
    it = data.begin();
    while (it != data.end()) {
        writer.Key(it->first.c_str());
        writer.String(it->second.c_str());
        md5string.append(it->first.c_str());
        md5string.append("=");
        md5string.append(it->second.c_str());
        md5string.append("&");
        it++;
    }
    // 待签名字串的 key
    md5string.append("key");
    md5string.append("=");
    md5string.append(this->md5key);
    // 进行 md5 签名
    MD5 md5(md5string);
    string sign = md5.md5();

    // 把签名字串也加入到 json 字串
    writer.Key("sign");
    writer.String(sign.c_str());
    writer.EndObject();

    // 进行 rsa 加密
    string strJ = json.GetString();
    string enData = "";
    for (int i = 0; i < strJ.length(); i += 117) {
        enData.append(EncodeRSAKeyFile(this->public_rsa_key, strJ.substr(i, 117)));
    }

    return base64_encode(reinterpret_cast<const unsigned char *>(enData.c_str()), enData.length());
}
