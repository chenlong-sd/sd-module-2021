<?php
/**
 * datetime: 2021/10/27 17:31
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace sdModule\layui\form\make;

interface MakeFormUnitI
{
    public function setOption(array $option);

    public function setDefault($default);

    public function setShowWhere(string $field, $value);

    public function setBoxId(string $box_id);

    public function setRequired();

    public function set();
}
