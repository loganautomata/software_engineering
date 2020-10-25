<?php

namespace app\course\facade;

use think\Facade;

class IP extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\course\business\IP';
    }
}