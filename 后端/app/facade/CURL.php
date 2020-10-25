<?php

namespace app\facade;

use think\Facade;

class CURL extends Facade
{
    protected static function getFacadeClass()
    {
    	return 'app\business\CURL';
    }
}