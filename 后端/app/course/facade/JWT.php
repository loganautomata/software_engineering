<?php

namespace app\course\facade;

use think\Facade;

class JWT extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\course\business\JWT';
    }
}