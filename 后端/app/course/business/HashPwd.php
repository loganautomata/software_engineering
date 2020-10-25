<?php

namespace app\course\business;

use app\course\model\User;
use app\course\facade\JWT;

class HashPwd
{
    //加密密码
    public function encrypt($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    //密码验证, 验证通过返回token.
    public function verify($username, $password)
    {
        $hashPassword = User::where('username', $username)->value('password'); //数据库中经过加密的密码

        //用户名, 密码验证.
        if($hashPassword && password_verify($password, $hashPassword))
        {
            return JWT::createToken($username);
        }
        else return false;
    }
}