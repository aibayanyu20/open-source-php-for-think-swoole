<?php
declare (strict_types = 1);

namespace app\middleware\api;

use app\common\exception\ApiException;
use app\common\lib\jwt\JwtCheckAuth;
use app\Request;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class CheckAuth
{
    /**
     * 处理请求
     *
     * @param Request $request
     * @param \Closure $next
     * @return Response
     * @throws ApiException
     */
    public function handle($request, \Closure $next)
    {
        $token = $request->header("Access-Token");
        // 开始解密当前的token数据
        if (empty($token)) apiError("请先登录到系统",10000);
        try {
            $data = (new JwtCheckAuth())->getData($token);
            // 拿到当前的数据
            $request->userId = $data->data->id;
        }catch (ExpiredException $expiredException){
            apiError("登录已过期",10000);
        }catch (SignatureInvalidException $signatureInvalidException){
            apiError("签名不正确",10000);
        }catch (BeforeValidException $beforeValidException){
            apiError("请稍后登录重试");
        }catch (\Exception $exception){
            apiError($exception->getMessage());
        }
        return  $next($request);
    }
}
