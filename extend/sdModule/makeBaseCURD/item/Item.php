<?php


namespace sdModule\makeBaseCURD\item;


use sdModule\makeBaseCURD\CURD;

interface Item
{
    /**
     * 初始化
     * Item constructor.
     * @param CURD $CURD
     */
    public function __construct(CURD $CURD);

    /**
     * @return mixed 创建
     */
    public function make();
}

