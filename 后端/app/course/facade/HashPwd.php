<?php

namespace app\course\facade;

use think\Facade;

class HashPwd extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\course\business\HashPwd';
    }
}