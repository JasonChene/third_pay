cmake_minimum_required(VERSION 3.10)
project(payrsa)

set(CMAKE_CXX_STANDARD 11)

set(INC_DIR "/usr/local/Cellar/openssl/1.0.2o_1/include" "~/CLionProjects/libs/curl-7.59.0/include" "~/CLionProjects/libs/rapidjson/include")
set(LINK_DIR "/usr/local/Cellar/openssl/1.0.2o_1/lib" "~/CLionProjects/libs/curl-7.59.0/lib")
include_directories(${INC_DIR})
link_directories(${LINK_DIR})
link_libraries(ssl curl)

add_executable(payrsa main.cpp md5.cpp global.cpp HttpRsaBuild.cpp HttpRsaBuild.h)
target_link_libraries(payrsa ssl crypto curl)