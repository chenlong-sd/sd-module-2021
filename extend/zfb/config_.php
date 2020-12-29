<?php
// 支付宝基本配置

return [
    // appid
//    'APPID' => env('A_LI.A_LI_APPID', ''),
    'APPID' => '',

    // 公钥
    'public_key' => '',

//    'private_key' => env('A_LI.A_LI_PRIVATE_KEY', ''),
    'private_key' =>  file_get_contents(__DIR__ . '/cert/private_cret.txt'),

    // 您的支付宝公钥证书文件路径
    'alipayCertPublicKey_RSA' => '',
    // 您的支付宝根证书文件路径
    'alipayRootCert' => '',
    // 您的应用公钥证书文件路径
    'appCertPublicKey' => '',

    // 默认 字符集
    'default_charset' => 'utf8',
    //
    'gateway' => 'https://openapi.alipay.com/gateway.do',

];
