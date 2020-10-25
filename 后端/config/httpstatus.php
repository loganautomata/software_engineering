<?php

return[
    'ok' => 200, //服务器成功返回用户请求的数据.[GET]
    'created' => 201, //用户新建或修改数据成功.[POST/PUT/PATCH]
    'accepted' => 202, //表示一个请求已经进入后台排队(异步任务).[*]
    'no_content' => 204, 
    'delete_content' => 207, //用户删除数据成功.[DELETE]
    'bad_request' => 400, //用户发出的请求有错误, 服务器没有进行新建或修改数据的操作.[POST/PUT/PATCH]
    'unauthorized' => 401, //表示用户没有权限(令牌, 用户名, 密码错误).[*]
    'forbidden' => 403, //表示用户得到授权(与401错误相对), 但是访问是被禁止的.[*]
    'not_found' => 404, //用户发出的请求针对的是不存在的记录, 服务器没有进行操作.[*]
    'method_not_allowed' => 405, //控制器, 方法不存在.[*]
    'not_acceptable' => 406, //用户请求的格式不可得(比如用户请求JSON格式, 但是只有XML格式).[GET]
    'gone' => 410, //用户请求的资源被永久删除, 且不会再得到的.[GET]
    'length_required' => 411,
    'precondition_failed' => 412,
    'unprocesable_entity' => 422, //当创建一个对象时, 发生一个验证错误.[POST/PUT/PATCH]
    'too_many_requests' => 429,
    'internal_server_error' => 500, //服务器发生错误.[*]
    'service_unavailable' => 503
];