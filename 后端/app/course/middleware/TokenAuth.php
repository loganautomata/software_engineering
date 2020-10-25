<?php

namespace app\course\middleware;

use app\course\facade\JWT;
use Exception;
use think\facade\Cache;

class TokenAuth
{
    public function handle($request, \Closure $next)
    {
        $token = $request->header('authorization');
        $token = trim(ltrim($token, 'Bearer'));

        //判断是否有Token.
        if (!$token || empty($token)) {
            return restful(false, '无Token', config('httpstatus.unauthorized'), config('httpstatus.unauthorized'), 'json');
        } else {
            try {
                $tokenData = JWT::getTokenData($token);
            } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
                return restful(false, '非法Token', config('httpstatus.unauthorized'), config('httpstatus.unauthorized'), 'json');
            } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
                return restful(false, '无效Token', config('httpstatus.unauthorized'), config('httpstatus.unauthorized'), 'json');
            } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
                $username = Cache::store('redis')->get($token);
                if ($username) {
                    $header = [
                        'Access-Control-Expose-Headers' => 'authorization',
                        'Cache-Control' => 'no-store',
                        'authorization' => 'Bearer ' . JWT::createToken($username)
                    ];
                    $request->usernameToken = $e->getData();
                    return $next($request)->header($header);
                } else {
                    return restful(false, 'Token过期', config('httpstatus.bad_request'), config('httpstatus.bad_request'), 'json');
                }
            } catch (Exception $e) {
                return restful(false, $e->getMessage(), config('httpstatus.bad_request'), config('httpstatus.bad_request'), 'json');
            }

            if ($token != Cache::store('redis')->get($tokenData['username'])) return restful(false, '无效Token', config('httpstatus.unauthorized'), config('httpstatus.unauthorized'), 'json');
            else {
                $request->usernameToken = $tokenData['username'];
                // $header = [
                //     'Access-Control-Expose-Headers' => 'authorization',
                //     'Cache-Control' => 'no-store',
                //     'authorization' => 'Bearer ' . JWT::createToken(Cache::store('redis')->get($token))
                // ];
                // return $next($request)->header($header);
                return $next($request);
            }
        }
    }
}
