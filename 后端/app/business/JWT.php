<?php

namespace app\business;

use Exception;
use Firebase\JWT\JWT as JsonWebToken;

class JWT
{
    //私钥
    protected $privateKey = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEAsIVfc2NXdGgR4atlipM/0mO5WyXQCWixinUXURA2I+bJx+ti
1OaEIPHX29eZWGxZvaWVIr9ZSw5ncGkN2trr/ywKSQHJGydr05k1r7xCdKw2v8n5
jknCpwow352dAZDY5rmykB3PVgYcSO0a4YILnTxZP5FFjRTtTO3XJw0lU8jtQw1H
AAz4QDQihd5t0CQFwSpfRg9EupIiEn/o7I1qt1NW5tuHJxzLVVTcreBymr1U2iGq
vW1kRtC/LYkPhG7BSC7CYfJOEA7hfEouU2Ft7aiXzRQjBZdJ0bP4EdJtnzb/DSUF
KhzE1S5rR4LsAN7Riwd9QAyULXBBe887UeyATQIDAQABAoIBAQCh1LSsgc/kvaBf
JxaBk/u384s0YBc4+Q2DpVOKEvW/tk8ZhNhn3SoXVgES4LcKVNN1RX/Til/fqsX0
TavREFG57/NB+WwZA+wb4uNQSKUYd+X9sLqk00SshXBHIiWWEOh5ppdo/ptd/14h
EZdKVUI2leQwnguIiz3/ADH28FtfUlhVHkZoI0emTWzgw8il0vULMhhciO/dLuri
qljLdsR7LjAzHjmslX+aGGx47oXoEL0/mo2+yUOWiQ3gmpiMu4hsSeZr4ruSP0OF
jmyXGBEhocyoh4xY2+f69P5yHpl0CETKbOPccpn2LgrqdnxgYoFlgb5RaoUV/X7D
PgGIxUgBAoGBAOPGd50kMrI7Hm1QQGoLGyvtnqYG2o9CMPsxLJdRkbtVRZAQvwIr
D1CkMDTzzerLk0reBhV3m5LprBDAuzhFtWNL9vv23zW9OZNRsYi8wFAftzrR0kch
OxwOrTuMOQ6tQbGzQlDJaw0tqeSWfhp8/Xb3vn97WsvtTp+R998CJeZNAoGBAMZl
AdaJFqUy40W2dc7t4mBtghrMgkZH1UvFj7AM7TUMRekn1MpNzJHilLCVXr92ACMh
Yta5q4MLDXD7W+YIYtfMFjkEVGTHabWDH2FfCDErw+zS4ZzmJxyMg5wC/lK8P4NF
wlAf4sl3H7HN2HKJ8IpGTh4kGR3Z/VphZS+E4gIBAoGAeKKmu01upEL3bIHye7eC
HpPtUJWUsAcolgGHUmVbP9J70z9rSqSvqdArz//IcbB8REyvADOm6/pirUTGY5lX
5k1XdD9eHMIpekLsOjVtzYHes4JpUk7hVYzYZw9vMLzGbG06lDr5PbOPpYenJjRI
sfeopzAX+KWo3I6crzoeBIkCgYB0tIZYRVxk4YKAR80VZSp9vu2CBju8TBVU9WqJ
DZGcWxPlC+FJAGsOhlMhbTzHNArwn3sdBSu3cpD9nV95+gTrHMsVz0412nh8ph2X
keenxS8D6Sl+uoTXbnEdHUWuNb7G4gpkR92I1eJ3Hbft6Obu8FyTrnh37vvyZsi3
I1wwAQKBgQCLEckyNB5sQa1uICeUux07xiaT35NUoKFjW6EMZe75A2TM7h6Ei1D2
nem+1GpXHF3Ec2ZGsRTwCTBz6OjVInkPdMNTK6tcyT7XaahyqTPOWW9zovTp27d4
6LEa0D0iPiix9Lyvwpri79+NTa0bw73so+bcOf+YcqP+Rb8ooUTTQg==
-----END RSA PRIVATE KEY-----
EOD;

    //公钥
    protected $publicKey = <<<EOD
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsIVfc2NXdGgR4atlipM/
0mO5WyXQCWixinUXURA2I+bJx+ti1OaEIPHX29eZWGxZvaWVIr9ZSw5ncGkN2trr
/ywKSQHJGydr05k1r7xCdKw2v8n5jknCpwow352dAZDY5rmykB3PVgYcSO0a4YIL
nTxZP5FFjRTtTO3XJw0lU8jtQw1HAAz4QDQihd5t0CQFwSpfRg9EupIiEn/o7I1q
t1NW5tuHJxzLVVTcreBymr1U2iGqvW1kRtC/LYkPhG7BSC7CYfJOEA7hfEouU2Ft
7aiXzRQjBZdJ0bP4EdJtnzb/DSUFKhzE1S5rR4LsAN7Riwd9QAyULXBBe887UeyA
TQIDAQAB
-----END PUBLIC KEY-----
EOD;

    //加密
    public function encode($data, $expiredTime = 600, $subDomain = 'http://loganren.xyz', $type = 'RS256')
    {
        $now = time();
        $payload = [
            // JWT的签发者
            "iss" => "https://api.loganren.xyz",
            // 接收该JWT的一方
            "aud" => $subDomain,
            // JWT创建时间(unix时间戳格式)
            "iat" => $now,
            // JWT的生命周期
            "exp" => $now + $expiredTime,
            // 如果当前时间在nbf里的时间之前则Token不被接受.
            "nbf" => $now + config('time.nbf'),
            // 当前token的唯一标识
            // "jti" => '9f10e796726e332cec401c569969e13e',
            // 主题
            "sub" => 'course',
            'data' => $data
        ];

        $token = JsonWebToken::encode($payload, $this->privateKey, $type);

        return $token;
    }

    // 解密
    public function decode($token)
    {
        JsonWebToken::$leeway = config('time.leeway'); //设置时间偏差
        try {
            $tokenData = JsonWebToken::decode($token, $this->publicKey, array('RS256'));
        } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            throw $e;
        } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            throw $e;
        } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }//解码
        return $tokenData; //返回data
    }
}