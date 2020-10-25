<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;
use think\facade\Request;

// Route::get('think', function () {
//     return 'hello,ThinkPHP6!';
// });

// Route::get('hello/:name', 'index/hello');

//测试路由

// Route::get('test', function () {
//    return json($_REQUEST);
// });

//MISS路由
Route::miss(function () {
    return restful(false, 'NOT FOUND', config('httpstatus.not_found'), config('httpstatus.not_found'));
});
