#include <openssl/rsa.h>
#include <openssl/err.h>
#include <openssl/pem.h>
#include <cassert>

#include "global.h"
#include <string>

static const std::string base64_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

static inline bool is_base64(unsigned char c) {
    return (isalnum(c) || (c == '+') || (c == '/'));
}

std::string base64_encode(unsigned char const *bytes_to_encode, unsigned int in_len) {
    std::string ret;
    int i = 0;
    int j = 0;
    unsigned char char_array_3[3];
    unsigned char char_array_4[4];

    while (in_len--) {
        char_array_3[i++] = *(bytes_to_encode++);
        if (i == 3) {
            char_array_4[0] = (char_array_3[0] & 0xfc) >> 2;
            char_array_4[1] = ((char_array_3[0] & 0x03) << 4) + ((char_array_3[1] & 0xf0) >> 4);
            char_array_4[2] = ((char_array_3[1] & 0x0f) << 2) + ((char_array_3[2] & 0xc0) >> 6);
            char_array_4[3] = char_array_3[2] & 0x3f;

            for (i = 0; (i < 4); i++)
                ret += base64_chars[char_array_4[i]];
            i = 0;
        }
    }

    if (i) {
        for (j = i; j < 3; j++)
            char_array_3[j] = '\0';

        char_array_4[0] = (char_array_3[0] & 0xfc) >> 2;
        char_array_4[1] = ((char_array_3[0] & 0x03) << 4) + ((char_array_3[1] & 0xf0) >> 4);
        char_array_4[2] = ((char_array_3[1] & 0x0f) << 2) + ((char_array_3[2] & 0xc0) >> 6);
        char_array_4[3] = char_array_3[2] & 0x3f;

        for (j = 0; (j < i + 1); j++)
            ret += base64_chars[char_array_4[j]];

        while ((i++ < 3))
            ret += '=';

    }

    return ret;

}

std::string base64_decode(std::string const &encoded_string) {
    int in_len = encoded_string.size();
    int i = 0;
    int j = 0;
    int in_ = 0;
    unsigned char char_array_4[4], char_array_3[3];
    std::string ret;

    while (in_len-- && (encoded_string[in_] != '=') && is_base64(encoded_string[in_])) {
        char_array_4[i++] = encoded_string[in_];
        in_++;
        if (i == 4) {
            for (i = 0; i < 4; i++)
                char_array_4[i] = base64_chars.find(char_array_4[i]);

            char_array_3[0] = (char_array_4[0] << 2) + ((char_array_4[1] & 0x30) >> 4);
            char_array_3[1] = ((char_array_4[1] & 0xf) << 4) + ((char_array_4[2] & 0x3c) >> 2);
            char_array_3[2] = ((char_array_4[2] & 0x3) << 6) + char_array_4[3];

            for (i = 0; (i < 3); i++)
                ret += char_array_3[i];
            i = 0;
        }
    }

    if (i) {
        for (j = i; j < 4; j++)
            char_array_4[j] = 0;

        for (j = 0; j < 4; j++)
            char_array_4[j] = base64_chars.find(char_array_4[j]);

        char_array_3[0] = (char_array_4[0] << 2) + ((char_array_4[1] & 0x30) >> 4);
        char_array_3[1] = ((char_array_4[1] & 0xf) << 4) + ((char_array_4[2] & 0x3c) >> 2);
        char_array_3[2] = ((char_array_4[2] & 0x3) << 6) + char_array_4[3];

        for (j = 0; (j < i - 1); j++) ret += char_array_3[j];
    }

    return ret;
}

// 加密
std::string EncodeRSAKeyFile(const std::string &strPemFileName, const std::string &strData) {

    if (strPemFileName.empty() || strData.empty()) {
        assert(false);
        return "";
    }
    FILE *hPubKeyFile = fopen(strPemFileName.c_str(), "rb");
    if (hPubKeyFile == NULL) {
        assert(false);
        return "";
    }
    std::string strRet;
    RSA *pRSAPublicKey = RSA_new();
    if (PEM_read_RSA_PUBKEY(hPubKeyFile, &pRSAPublicKey, 0, 0) == NULL) {
        assert(false);
        return "";
    }

    int nLen = RSA_size(pRSAPublicKey);
    char *pEncode = new char[nLen + 1];
    int ret = RSA_public_encrypt(strData.length(), (const unsigned char *) strData.c_str(), (unsigned char *) pEncode,
                                 pRSAPublicKey, RSA_PKCS1_PADDING);
    if (ret >= 0) {
        strRet = std::string(pEncode, ret);
    }
    delete[] pEncode;
    RSA_free(pRSAPublicKey);
    fclose(hPubKeyFile);
    CRYPTO_cleanup_all_ex_data();

    return strRet;
}

// 解密
std::string DecodeRSAKeyFile(const std::string &strPemFileName, const std::string &strData) {

    if (strPemFileName.empty() || strData.empty()) {
        assert(false);
        return "";
    }
    FILE *hPriKeyFile = fopen(strPemFileName.c_str(), "rb");
    if (hPriKeyFile == NULL) {
        assert(false);
        return "";
    }
    std::string strRet;
    RSA *pRSAPriKey = RSA_new();
    if (PEM_read_RSAPrivateKey(hPriKeyFile, &pRSAPriKey, 0, 0) == NULL) {
        assert(false);
        return "";
    }
    int nLen = RSA_size(pRSAPriKey);
    char *pDecode = new char[nLen + 1];

    int ret = RSA_private_decrypt(strData.length(), (const unsigned char *) strData.c_str(), (unsigned char *) pDecode,
                                  pRSAPriKey, RSA_PKCS1_PADDING);
    if (ret >= 0) {
        strRet = std::string((char *) pDecode, ret);
    }
    delete[] pDecode;
    RSA_free(pRSAPriKey);
    fclose(hPriKeyFile);
    CRYPTO_cleanup_all_ex_data();

    return strRet;
}

std::string generate_sn() {
    time_t t = time(0);
    char yhdhms[15];
    strftime(yhdhms, sizeof(yhdhms), "%Y%m%d%H%M%S", localtime(&t));
    std::string ret(yhdhms);
    return ret;
}
