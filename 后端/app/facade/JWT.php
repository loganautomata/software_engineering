<?php

namespace app\facade;

use think\Facade;

class JWT extends Facade
{
    protected static function getFacadeClass()
    {
    	return 'app\business\JWT';
    }
}