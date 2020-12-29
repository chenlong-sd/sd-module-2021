<?php
/**
 *
 * Pay.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/21 15:03
 */


class Pay
{
    public function testPage()
    {
        $page = \zfb\pay\SCALiPay::page();

        $html = $page->setReturnUrl('https://www.mlscd.cn')
            ->setNotifyUrl('https://www.mlscd.cn')
            ->easyCreate('支付宝电脑网页支付', 88);

        $page->out_trade_no; // 获取对应订单号存储处理

        return $html;
    }

    public function testWap()
    {
        $wap = \zfb\pay\SCALiPay::wap();

        $html = $wap->setReturnUrl('https://www.mlscd.cn')
            ->setNotifyUrl('https://www.mlscd.cn')
            ->easyCreate('支付宝手机网页支付', 88);

        $wap->out_trade_no; // 获取对应订单号存储处理

        return $html;
    }

    public function testApp()
    {
        $wap = \zfb\pay\SCALiPay::app();

        $param = $wap->setReturnUrl('https://www.mlscd.cn')
            ->setNotifyUrl('https://www.mlscd.cn')
            ->easyCreateParam('支付宝手机APP支付', 88);

        $wap->out_trade_no; // 获取对应订单号存储处理

        return $param; // array
    }


}

