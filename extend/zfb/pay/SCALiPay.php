<?php
/**
 *
 * ALiPay.php
 * User: ChenLong <vip_chenlong@163.com>
 * DateTime: 2020/7/21 14:32
 */


namespace zfb\pay;


class SCALiPay
{
    /**
     * @param null $uid
     * @return Page
     */
    public static function page($uid = null)
    {
        return new Page($uid);
    }

    /**
     * @param null $uid
     * @return Wap
     */
    public static function wap($uid = null)
    {
        return new Wap($uid);
    }

    public static function app($uid = null)
    {
        return new App($uid);
    }
}

