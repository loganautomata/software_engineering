<?php

namespace app\course\controller;

use app\BaseController;

class Error extends BaseController{
    public function __call($name, $arguments)
    {
        return restful(false, $this->request->controller().'控制器未定义', config('httpstatus.method_not_allowed'), config('httpstatus.method_not_allowed'));
    }
}