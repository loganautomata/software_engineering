<?php

declare(strict_types=1);

namespace app\course\controller;

use think\exception\ValidateException;
use app\course\model\User as UserModel;
use app\course\validate\User as UserValidate;
use app\BaseController;
use app\course\facade\HashPwd;
use app\course\facade\JWT;
use think\Exception;
use think\Request;

class User extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    /**
     * 
     *
     * @return \think\Response
     */
    public function create()
    {
    }

    /**
     * 新建用户, 格式正确返回True, 错误返回false.
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save()
    {
        $data = $this->request->post(['username', 'password', 'passwordRe']);

        //请求格式验证.
        try {
            validate(UserValidate::class)->scene('register')->check($data);
        } catch (ValidateException $e) {
            return restful(false, $e->getError(), config('httpstatus.bad_request'), config('httpstatus.bad_request'));
        }

        //数据库中新建用户.
        UserModel::create([
            'username' => $data['username'],
            'password' => HashPwd::encrypt($data['password']),
            'status' => 0,
            'create_time' => $this->request->time(),
            'last_login_time' => $this->request->time(),
            'last_login_ipv4' => ip2long($this->request->ip())
        ]);

        return restful(true, '注册成功', config('httpstatus.created'), config('httpstatus.created'));
    }

    /**
     * 获得单个用户的信息
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($username)
    {
        if ($this->request->usernameToken === $username) {
            $user = UserModel::where('username', $username)->findOrEmpty();

            if ($user->isEmpty()) {
                return restful(false, '用户不存在', config('httpstatus.not_found'), config('httpstatus.not_found'));
            } else {
                $data = $user->toArray();
                return restful([
                    'username' => $data['username'],
                    'status' => $data['status'],
                    'create_time' => $data['create_time'],
                    'last_login_time' => date('Y-m-d H:i:s', $data['last_login_time']),
                    'last_login_ipv4' => long2ip($data['last_login_ipv4'])
                ], '用户信息', config('httpstatus.ok'));
            }
        } else return restful(false, '用户名与Token不对应', config('httpstatus.forbidden'), config('httpstatus.forbidden'));
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($username)
    {
        //
    }

    /**
     * 更新资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update($username)
    {
        if ($this->request->usernameToken === $username) {
            $user = UserModel::where('username', $username)->findOrEmpty();
            $status = UserModel::where('username', $username)->value('status');
            if ($status === 0) {
                $msg = '';
                if ($data = $this->request->put(['username'])) {
                    try {
                        validate(UserValidate::class)->scene('updateUsername')->check($data);
                    } catch (ValidateException $e) {
                        $msg = $msg . $e->getMessage();
                    }
                    $user->save($data);
                }
                if ($this->request->put('password') && $this->request->put('passwordRe') && $data = $this->request->put(['password', 'passwordRe'])) {
                    try {
                        validate(UserValidate::class)->scene('updatePassword')->check($data);
                    } catch (ValidateException $e) {
                        $msg = $msg . $e->getMessage();
                    }
                    $user->save([
                        'password' => HashPwd::encrypt($data['password']),
                    ]);
                    JWT::deleteToken($username);
                }
                if ($msg === '') return restful(true, '更新成功', config('httpstatus.ok'));
                else return restful(true, $msg, config('httpstatus.bad_request'));
            } else if ($status === 1) {
                return restful(false, '异地登录', config('httpstatus.forbidden'), config('httpstatus.forbidden'));
            } else {
                return restful(false, '用户名不存在', config('httpstatus.not_found'), config('httpstatus.not_found'));
            }
        } else return restful(false, '用户名与Token不对应', config('httpstatus.forbidden'), config('httpstatus.forbidden'));
    }

    /**
     * 删除指定用户
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($username)
    {
        if ($this->request->usernameToken === $username) {
            $status = UserModel::where('username', $username)->value('status');

            if ($status === 0) {
                UserModel::where('username', $username)->delete();
                JWT::deleteToken($username);
                return restful(true, '注销成功', config('httpstatus.delete_content'), config('httpstatus.delete_content'));
            } else if ($status === 1) {
                return restful(false, '异地登录', config('httpstatus.forbidden'), config('httpstatus.forbidden'));
            } else {
                return restful(false, '用户名不存在', config('httpstatus.not_found'), config('httpstatus.not_found'));
            }
        } else return restful(false, '用户名与Token不对应', config('httpstatus.forbidden'), config('httpstatus.forbidden'));
    }
}
