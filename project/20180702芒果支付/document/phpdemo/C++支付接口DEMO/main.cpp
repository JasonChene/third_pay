#include <curl/curl.h>

#include <iostream>
#include <string>
#include <cstring>
#include <map>

#include "global.h"
#include "md5.h"
#include "HttpRsaBuild.h"

// 加密密钥
#define MD5KEY "c8be6f707ca0964ec7a7ef8ee9da013c"
// 商户号
#define MEMBER_CODE "2017090631"
// rsa 公私钥
#define PUBLIC_RSA_KEY "public_key_test.pem"
#define PRIVATE_RSA_KEY "private_key_test.pem"

using namespace std;

/**
 * curl 的回调函数
 * @param void * data
 * @param size_t size
 * @param size_t nmemb
 * @param void * content
 * @return
 */
size_t http_data_writer(void *data, size_t size, size_t nmemb, void *content) {

    long totalSize = size * nmemb;
    std::string *symbolBuffer = (std::string *) content;
    if (symbolBuffer) {
        symbolBuffer->append((char *) data, ((char *) data) + totalSize);
    }

    return totalSize;
}

int main() {
    /**
     * 支付请求数据参数
     */
    map<string, string> data;
    // 支付类型
    data["type_code"] = "zfbh5";
    // 商户订单号
    data["down_sn"] = generate_sn();
    // 主题
    data["subject"] = "test for cpp";
    // 交易金额
    data["amount"] = "100.00";
    // 异步通知地址，回调用
    data["notify_url"] = "http://www.google.com/";

    // rsa 数据加密
    HttpRsaBuild hrb(MD5KEY, PUBLIC_RSA_KEY, PRIVATE_RSA_KEY);
    string cipherData = hrb.encrypt(data);

    // 拼接 post 参数
    string post = "";
    post += "member_code=";
    post += MEMBER_CODE;
    post += "&cipher_data=";
    post += cipherData;

    // 发送网络请求
    CURL *curl;
    CURLcode res;
    curl_global_init(CURL_GLOBAL_ALL);
    curl = curl_easy_init();

    struct curl_slist *list = NULL;
    std::string respData;

    if (curl) {
        list = curl_slist_append(list, "Content-type: application/x-www-form-urlencoded;charset=UTF-8");
        curl_easy_setopt(curl, CURLOPT_URL, "http://www.magopay.net/api/trans/pay");
        curl_easy_setopt(curl, CURLOPT_HTTPHEADER, list);
        curl_easy_setopt(curl, CURLOPT_POST, 1L);
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post.c_str());
        curl_easy_setopt(curl, CURLOPT_CONNECTTIMEOUT, 20L);
        curl_easy_setopt(curl, CURLOPT_TIMEOUT, 20L);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, http_data_writer);
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, (void *) &respData);

        res = curl_easy_perform(curl);
        curl_slist_free_all(list);
        if (res != CURLE_OK) {
            fprintf(stderr, "curl_easy_perform() failed: %s\n", curl_easy_strerror(res));
        }

        curl_easy_cleanup(curl);
    }
    curl_global_cleanup();

    std::cout << "respData: " << respData << std::endl;

    return 0;
}
