<?php

use think\facade\Route;

//MISS路由
Route::miss(function () {
    return restful(false, 'NOT FOUND', config('httpstatus.not_found'), config('httpstatus.not_found'));
});

//CourseAPI路由
Route::group('v0.5', function () {
    //Token路由
    Route::group('token', function () {
        Route::post('', 'course/Token/create');
        Route::delete(':username', 'course/Token/delete')->middleware(['UserAuth']);
    });

    //User路由
    Route::group('user', function () {
        Route::get(':username', 'course/User/read')->middleware(['UserAuth']);
        Route::post('', 'course/User/save');
        Route::delete(':username', 'course/User/delete')->middleware(['UserAuth']);
        Route::put(':username', 'course/User/update')->middleware(['UserAuth']);
    });
})->allowCrossDomain();
