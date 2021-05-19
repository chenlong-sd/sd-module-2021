<?php
// 微信基本配置
return [
    'base' => [
        // 可以多个配置
        'common' => [
            // appid
            'appId' => env('WE_CHAT.APPID', ''),

            // secret
            'appSecret' => env('WE_CHAT.SECRET', ''),
        ],


    ],

    // 通用支付
    'common_pay' => [
        'appid' => env('WE_CHAT_PAY.W_P_APPID', ''),
        'mch_id' => env('WE_CHAT_PAY.W_P_MCH_ID', ''),
        'key' => env('WE_CHAT_PAY.W_P_KEY', ''),
        'v3_key' => env('WE_CHAT_PAY.API_V3_KEY', ''),
        'serial_no' => env('WE_CHAT_PAY.SERIAL_NO', ''),
    ],
    // 证书
    'cert' => [
        'cert' => '',  // 证书路径
        'key' => ''    // 秘钥路径
    ],

    // App支付
    'app_pay' => [],
    // 小程序支付
    'small_pay' => [],
    // 扫码支付
    'scan_pay' => [],
    // 提现
    'cash' => [],

    // 公众号域名验证token
    'domainVerifyToken' => 'sc_token_verify',

    // 消息加密秘钥
    'encodingAppKey' => '3cR8uPWftdin4nOfS9Axpu7zVDr0vkq5WD8BxJfY6I8',

    // 开启安全模式
    'safeMode' => true,

    // 被动回复消息回调类
    'callbackClass' => \weChat\WXDemo::class,

    // 存储 access_token 值的键名
    'tokenKey' => 'wx_access_token',

    // 存储网页授权 access_token 值的键名
    'authTokenKey' => 'wx_web_access_token',

    // 存储网页授权 refresh_token 值的键名
    'refreshTokenKey' => 'wx_web_refresh_token',

    // 存储js_api_ticket 的键值
    'jsApiTicketKey' => 'js_api_ticket_key',

];
