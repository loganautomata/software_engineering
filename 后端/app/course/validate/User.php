<?php
declare (strict_types = 1);

namespace app\course\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username|用户名' => 'require|unique:user|chsAlphaNum|max:13',
        'password|密码' => 'require|alphaDash|min:8|max:16',
        'passwordRe|确认密码' => 'confirm:password',
        'email|邮箱' => 'email',

        '__token__' => 'require|token'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'username.require' => '用户名不得为空',
        'username.unique' => '用户名重复',
        'username.max' => '用户名不得多于十三位',
        'password.require' => '密码不得为空',
        'password.min' => '密码不得少于八位',
        'password.max' => '密码不得多于十六位',
        'passwordRe.confirm' => '两次输入的密码不相同'
    ];

    /**
     * 定义场景信息
     * 格式：'场景名' =>  ['字段名1','字段名2'...]
     *
     * @var array
     */
    protected $scene = [
        'register'  =>  ['username', 'password', 'passwordRe'],
        'updateUsername' => ['username'],
        'updatePassword' => ['password', 'passwordRe']
    ];
}