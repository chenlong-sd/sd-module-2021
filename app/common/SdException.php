<?php
/**
 *
 * SdException.php
 * User: ChenLong
 * DateTime: 2020/4/29 15:14
 */


namespace app\common;

use Throwable;

/**
 * 自定义的异常
 * Class SdException
 * @package app
 * @author chenlong <vip_chenlong@163.com>
 */
class SdException extends \Exception
{
    public function __construct($msg, $code = 0, Throwable $previous = null)
    {
        if (is_array($msg)){
            list($message, $var) = $msg;
            $message = lang($message, $var);
        }else{
            $message = lang($msg);
        }
        parent::__construct($message, $code, $previous);
    }
}

