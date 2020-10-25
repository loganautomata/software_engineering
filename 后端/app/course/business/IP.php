<?php

namespace app\course\business;

use app\facade\CURL;

class IP
{
    function getInfo($ip)
    {
        $url = 'http://ip-api.com/json/'.$ip.'?lang=zh-CN';

        $data = CURL::get($url);

        return $data;
    }
}