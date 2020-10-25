<?php
use think\Response;
// 应用公共文件

//返回RESTful格式的数据
function restful($data, $msg, $code, $httpStatus = 200, $type = 'json') : Response
{
    $result = [
        'code' => $code,
        'message' => $msg,
        'data' => $data
    ];

    return Response::create($result, $type, $httpStatus);
}