<?php

use sdModule\timedTask\task\{Test, Test2};

return [
    Test::class => '1 * * * * *',
    Test2::class => '0 * * * * *',
];

