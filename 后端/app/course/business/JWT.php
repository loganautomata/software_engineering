<?php

namespace app\course\business;

use app\business\JWT as JsonWebToken;
use Exception;
use think\facade;
use think\facade\Cache;

class JWT
{
    protected $token;
    protected $domain; //前端域名

    function __construct()
    {
        $this->token = new JsonWebToken();
        $this->domain = 'https://test.loganren.xyz';
    }

    //获得新Token并存入redis.
    public function createToken($username)
    {
        $token = $this->token->encode($username, config('time.token'), $this->domain);
        $oldToken = Cache::store('redis')->get($username);
        if (!$oldToken) {
            Cache::store('redis')->set($username, $token, config('time.redis_token'));
            Cache::store('redis')->set($token, $username, config('time.redis_token'));
        } else {
            Cache::store('redis')->delete($username);
            Cache::store('redis')->delete($oldToken);
            Cache::store('redis')->set($username, $token, config('time.redis_token'));
            Cache::store('redis')->set($token, $username, config('time.redis_token'));
        }
        return $token;
    }

    //删除指定用户的Token.
    public function deleteToken($username)
    {
        if (Cache::store('redis')->get($username)) {
            Cache::store('redis')->delete(Cache::store('redis')->get($username));
            Cache::store('redis')->delete($username);
            return true;
        } else return false;
    }

    //解密Token.
    public function getTokenData($token)
    {
        try {
            $data = $this->token->decode($token);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            throw $e;
        } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            throw $e;
        } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }

        $data = (array)$data;
        $data['username'] = $data['data'];
        unset($data['data']);

        return $data;
    }

    //刷新Token.
    public function refreshToken($token)
    {
    }
}
