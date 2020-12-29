<?php
/**
 * Date: 2020/9/26 10:34
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\layui\defaultForm\formUnit;


class Custom extends UnitBase
{

    /**
     * @param string $attr
     * @return mixed|string
     */
    public function getHtml(string $attr)
    {
        return $this->select_data['html'];
    }
}
