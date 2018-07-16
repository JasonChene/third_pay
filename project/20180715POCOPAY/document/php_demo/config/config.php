<?php
return [
    'url'           => 'http://api.pocopayment.com/v2',
    'merchant_no'   => '1180419212326612',          //商户号
    'partner_id'    => '10802791000000638617',          //商户签约ID
    'version'       => 'v1',        //版本号
    'sign_type'     => 'RSA',       //签名方式

    'private_key'   => '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDLqFEdZBZj0GXM
TYdpGWKZEnP1l3ILAr4yI0RNjtQQl+n0WmWDAyuGef9wo/QpNLgJcBO1VQexm1S5
BmH6LU5Tbd2rsGPhetL9HI47b5O9Dq4lqlOD0pk1kwAQ8LdcE/P/UpynzIZNyYH1
TyWwWH162uYyQmchLt1Lq7oZOHyMO90daqghII36GXM8VwIeyb8k02oo13VViCOl
yBWyUMeJchM13QlYGafbLgICwW6XpwutzIqh/H6JWZK57QXadF7iYLHNTpeGoWv0
/IaHfEFcOV5ldexySLRxp//p1UPrSI5cPyTVlx5SbLRqHxulUsTAwfs8x7FDVJbW
ihEOw2+tAgMBAAECggEBALg3g0/zpfEZo8te4bqmtLJDp9Kh8A2XqJVPo0wIgziT
QZO/ZQ1SA7/7gJzSIVxYjJQ05g7jYDc+iy9mX5/d9Xiv65CO2HowEMPVXa7Tqa6c
pdf6LtsR13ZHhwrLwwAuKEpu3rdF4MJiIQTVWAEgujgz43/Y4tJ4QsJdDtQbbUWm
jjMkzoGhVPL5Jo7d6KoaxIWq9O6N5IHYytZxCfcaZq9ZcAeMACa8Eb7jZNfUOlmg
I9HkC0IGUVrp2I506ViYZ6Gcai5T3aRXOxhzFztdNmZCH63tD+DJutCiKUYAl6sc
WUeP16cspPsEuWSQP0fvBCrHSS3b1xR2wtsn5BZDPgECgYEA+J4frJcJfp6Pyv72
Fr6plO+ZIZpoXXDqaR8ia2oa0+celS/NRndPPLHEPv+X0foj49KqF6IHPlYSpThW
SIs3k440CmIc1JP1q5AMoxgfPPHj7Z08lcZegHczkaQxDOEjfukYPbmACxDQvURe
Vl3+Pw5ihqP4FIjcWrhCfUwDloECgYEA0bRtOYzLSnd7VKk7uzQY97pjZX8Gz9zs
ee4anESo0Fc7kQ4UqxN86fauBiz7VbmMuBP3K1626skNQw1+rZnr1f/iETQpCLyp
OS+iUW6GDmFXLl+Re4r2dML7BNZThKfiKpmesLyKImk7YvfrPRRPpXWohw2epnlH
kJ340APSey0CgYAc2o0mhKjvbwuVLZ316c7YoC4PflIadh5ecOSXvsq6SRp9ifyy
7undS2xRO2ytS/CaYjqnX4CjtW2Yiz9IUA/1Kg3UHqrl6P4cGS2+R/BoLFKdoyAC
6fDRzAPKJVoj+oUaF4desoavwhXXwXWJZIM7YjoBEkqaZ3/bb4MX85h/AQKBgGSi
/MPgkHLVMien65VkZBMGrMq5kjBQ6l99Z+HUhVsucdSj+EE9YfX5vvwRvDAgkqqI
UlbK5md4QtgJ8uAm/Om/GeN9r6+UsnVSKciAjO77wdXsYSnzv2C7bbGdlYkU9FHT
VTel03i+HZTaO1cdlzdlZpIhdCCaFUEOvqg9HValAoGAbxL6DsDOFOWsmYDY7GO3
455MbWTX9KBfmNNnGHVj5ANjMMEJITiNR9jo7sd+0TgPjTkCgqkpd+P+ihWMv4Ea
+iUofBdH0buc/6ik1N6U1e3Nc5l9TDsDoXFUxssJi4UD+uf1nlhT5ylw/3uLU1lh
+BYf78m8zrae2QlmQn+3GxE=
-----END PRIVATE KEY-----',          //商户私钥

    'public_key'    => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC+XbU4UTWi+lL1yzvybHMhr0uVm60bCBCXkzdO+IyODaS2oWvO0VPVAc6U3J823kBcfPktq1rrnwCxemAx/nfPcATVzH/6foZ+qx5lsx8kcDXuUE8aM5gbW+eb01WgfgewWchG7ljPNePOPeOSRcptObEmUBtZeBoTQAMqZkPn3QIDAQAB',          //平台公钥  用于返回数据验签
];
