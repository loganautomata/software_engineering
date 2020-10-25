<?php

namespace app\course\controller;

use app\BaseController;
use app\course\facade\JWT;

class Index extends BaseController
{
    public function index()
    {
        return 'Hello, World.';
    }

    public function testPwd()
    {
        $hashPassword = password_hash('kawaii', PASSWORD_DEFAULT);
        dump($hashPassword);
        dump(strLen($hashPassword));
        if (password_verify('kawaii', $hashPassword)) {
            echo '密码正确';
        } else {
            echo '密码错误';
        }
    }

    public function testToken()
    {
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvYXBpLmxvZ2FucmVuLnh5eiIsInN1YiI6Imh0dHBzOlwvXC90ZXN0LmxvZ2FucmVuLnh5eiIsImlhdCI6MTYwMzM0NzAwNSwiZXhwIjoxNjAzMzQ3NjA1LCJkYXRhIjoiU2FnaXJpIn0.BqQAhlrpxAOlNDSE1lbX4A-fRASxpzc2K4XvDcKVgauKszXQJm6RM6m5vxsMdaABQWFtDNtS-RiERFIDOYDDXJAzBEK_7zvwz992qMvRvVdaz8i6sYRwytLv8mxeG4ZpkG-tadeXKsNp3GWxn4u7Pg5QnEZ65Dl5-IyOODWZWVjnySRfPmzbN3pL_YkOiBWAyad0t72aIAtzoqPB_UlqZfQcM52jaj-R0MXFfHLhdrn3E7PWBz2TNtBVnxjxltDWla3E6_oiJ-Fd2FN27zm48ubkLj_T3OMNAg0KyfA2SyJHOhJkbJSM_X_DgR4DPAlgOgYFXbsJQYntxN2l4kPYKQ";
        $tokenData = JWT::getTokenData($token);
        dump($tokenData);
    }
}