<?php

use think\facade\Env;

return [
    'default' => Env::get('filesystem.driver', 'local'),
    'disks'   => [
        'local'  => [
            'type' => 'local',
            'root' => app()->getRuntimePath() . 'storage',
        ],
        'public' => [
            'type'       => 'local',
            'root'       => app()->getRootPath() . 'public/',
            'url'        => '/',
            'visibility' => 'public',
        ],
        'file' => [
            'type'       => 'local',
            'root'       => app()->getRootPath() . 'upload/',
        ]
        // 更多的磁盘配置信息
    ],
];
