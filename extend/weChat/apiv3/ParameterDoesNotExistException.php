<?php
/**
 * datetime: 2021/9/7 23:34
 * user    : chenlong<vip_chenlong@163.com>
 **/


namespace weChat\apiv3;

/**
 * 参数不存在
 * Class ParameterDoesNotExistException
 * @package weChat\apiv3
 * @author chenlong<vip_chenlong@163.com>
 * @date 2021/9/7
 */
class ParameterDoesNotExistException extends \Exception
{
    /**
     * ParameterDoesNotExistException constructor.
     * @param BaseParams $baseParams
     * @param string $param
     * @author chenlong<vip_chenlong@163.com>
     * @date 2021/9/7
     */
    public function __construct(BaseParams $baseParams, string $param)
    {
        $class = get_class($baseParams);
        $message = "The parameter $param cannot be found in the $class class";
        parent::__construct($message);
    }
}
