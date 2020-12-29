<?php
// =======================================================
// 微信支付示例 2020-08-05 18:34
// chenlong <vip_chenlong@163.com>
// =======================================================


$XUnifiedOrder = new \weChat\pay\WeChatPay(\weChat\pay\WeChatPay::JS_API);
$XUnifiedOrder->notify_url  = 'http://xa.scchyx.com/index.php/api/Notify';   // 通知地址
$XUnifiedOrder->body        = '积分充值';                                          //商品描述
$XUnifiedOrder->total_fee   = 1;                                               //总金额(分)
$XUnifiedOrder->openid      = 'openid';                             //用户的openid

/// =============================== 以下二选一 ====================

/** 需要请求数据的中间业务调用 */
try {
    $prepay = $XUnifiedOrder->request();
    // 做点其他的，自己的业务（如果需要
    $data = $XUnifiedOrder->requestPayData($prepay['prepay_id'] ?? 0);
} catch (\app\common\SdException $exception) {
    // 下单失败
}


/** 不需要请求数据的中间业务调用 */
try {

    $data = $XUnifiedOrder->requestPayData(true);
} catch (\app\common\SdException $exception) {
    // 下单失败
}



// =======================================================
// 微信提现示例 2020-08-06 10:31
// chenlong <vip_chenlong@163.com>
// =======================================================



$putForward = new \weChat\pay\PutForward();
$putForward->openid = 'eqwe'; // 用户的openid
$putForward->amount = 1;  // 要提取的金额
$putForward->desc = '支付提取描述';    // 支付提取描述
$res = $putForward->request();  // 返回的是微信的返回数据，没有判断成功与否，已转成数组，详细自己打印看

// 查询调用示例：
$res = $putForward->query('商户订单号');     // 返回的是微信的返回数据，没有判断成功与否，已转成数组，详细自己打印看
