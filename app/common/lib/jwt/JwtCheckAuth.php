<?php
/**
 * @time 2020/11/9 9:07 下午
 * @author aibayanyu
 */

namespace app\common\lib\jwt;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use think\Exception;
use think\facade\Cache;

class JwtCheckAuth
{
    /**
     * @var Factory
     */
    private $factory;

    public function __construct(){
        $this->factory = new Factory;
    }

    /**
     * 生成token
     * @time 2020/11/9 9:25 下午
     * @param $data
     * @return string
     * @throws Exception
     * @author aibayanyu
     */
    public function getToken($data){
        $this->factory->setData($data);
        // 获取当前的token
        $token = $this->factory->createToken();
        // 拿到token存入到当前的缓存中
        Cache::set($token,$data,$this->factory->getExpire());
        // 已经存入缓存中
        return $token;
    }

    /**
     * 获取数据
     * @time 2020/11/9 9:25 下午
     * @param $token
     * @return object
     * @throws \Exception
     * @author aibayanyu
     */
    public function getData($token){
        if (!Cache::has($token)) throw new ExpiredException("当前的token已过期");
        // 未过期的话，开始进行二次的测试
        try {
            return $this->factory->getData($token);
        }catch (SignatureInvalidException $signatureInvalidException){
            // 签名不正确
            throw $signatureInvalidException;
        }catch (BeforeValidException $beforeValidException){
            // 签名还未到使用的时间
            throw $beforeValidException;
        }catch (ExpiredException $expiredException){
            // 签名已过期
            throw $expiredException;
        }catch (\Exception $exception){
            // 其他错误
            throw $exception;
        }
    }
}