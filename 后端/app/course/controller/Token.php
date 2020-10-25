<?php

declare(strict_types=1);

namespace app\course\controller;

use app\BaseController;
use app\course\facade\HashPwd;
use app\course\model\User;
use app\course\facade\JWT;
use app\course\facade\IP;
use Exception;
use think\Request;

class Token extends BaseController
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
     * 服务端在缓存中创建Token, 在data中返回Token.
     *
     * @return \think\Response
     */
    public function create()
    {
        $data = $this->request->post(['username', 'password']);

        //检验请求格式是否正确.
        if (!array_key_exists('username', $data) || !array_key_exists('password', $data) || !$data['username'] || !$data['password']) {
            return restful(false, '请求格式错误', config('status.login_failed'), config('httpstatus.bad_request'));
        } else {
            //检验密码是否正确.
            $accessToken = HashPwd::verify($data['username'], $data['password']);
            if (!$accessToken) {
                return restful(false, '密码或用户名错误', config('status.login_failed'), config('httpstatus.bad_request'));
            } else {
                $ip_last = long2ip(User::where('username', $data['username'])->value('last_login_ipv4')); //上次登录的IP地址.
                $ip_now = $this->request->ip(); //本次登录IP
                $time_last = date('Y-m-d H:i:s', User::where('username', $data['username'])->value('last_login_time')); //上次登录的时间.
                $ads_l = IP::getInfo($ip_last);
                $ads_n = IP::getInfo($ip_now);
                $address_last = $ads_l['country'] . $ads_l['regionName'] . $ads_l['city']; //上次登录地址
                $address_now = $ads_n['country'] . $ads_n['regionName'] . $ads_n['city']; //本次登录地址

                //检验异地登录
                if ($address_last === $address_now) {
                    $status = 0;
                    $code = config('status.login_successed');
                } else {
                    $status = 1;
                    $code = config('status.login_exception_ip');
                }


                //更新数据库数据
                User::where('username', $data['username'])->save([
                    'last_login_time' => $this->request->time(),
                    'last_login_ipv4' => ip2long($ip_now),
                    'status' => $status
                ]);

                return restful([
                    'token' => $accessToken,
                    'last_login_ipv4' => $address_last,
                    'last_login_time' => $time_last,
                ], '登录成功', $code, config('httpstatus.created'));
            }
        }
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定Token
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($username)
    {
        if ($this->request->usernameToken === $username) {
            if (JWT::deleteToken($username)) return restful(true, '退出成功', config('httpstatus.delete_content'), config('httpstatus.delete_content'));
            else return restful(false, '退出失败', config('httpstatus.bad_request'), config('httpstatus.bad_request'));
        } else return restful(false, '用户名与Token不对应', config('httpstatus.forbidden'), config('httpstatus.forbidden'));
    }
}
