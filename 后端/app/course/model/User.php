<?php

namespace app\course\model;

use think\model;

class User extends Model
{
    public function exist($username)
    {
        $user = $this->where('username', $username)->findOrEmpty();

        if (!$user->isEmpty()) {
            return true;
        } else return false;
    }
}
