<?php
/**
 * @time 2020/11/9 7:49 上午
 * @author aibayanyu
 */

namespace app\controller\api\v1;


use app\BaseController;
use app\common\exception\ApiException;
use app\common\lib\jwt\JwtCheckAuth;
use app\model\mysql\Users;
use app\validate\UserValidate;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\facade\Cache;
use think\response\Json;

class User extends BaseController
{
    /**
     * 用户登录
     * @time 2020/11/16 9:32 下午
     * @param UserValidate $userValidate
     * @param Users $users
     * @return Json
     * @throws ApiException
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException|Exception
     * @author aibayanyu
     */
    public function login(UserValidate $userValidate,Users $users){
        // 验证当前的登录信息
        $userValidate->goCheck('login');
        // 当前的登录信息验证通过
        $user = $users->checkLoginUser();
        // 拿到当前用户的基本信息开始加密并进行保存
        $token = (new JwtCheckAuth())->getToken($user);
        // 保存到本地
        Cache::set($token,$user);
        return apiDataSuccess(['accessToken'=>$token]);
    }

    /***
     * 获取用户的基本信息
     * @time 2020/11/16 9:46 下午
     * @param Users $users
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author aibayanyu
     */
    public function getUserInfo(Users $users){
        // 开始获取用户的基本信息
        $userInfo = $users->getUserInfo();
        return apiDataSuccess($userInfo);
    }

    /**
     * 修改密码
     * @time 2020/11/17 10:24 下午
     * @param Users $users
     * @param UserValidate $userValidate
     * @return Json
     * @author aibayanyu
     */
    public function changePass(Users $users,UserValidate $userValidate){
        // 修改密码
        $userValidate->goCheck('update');
        $users->getChangePass();
        return apiSuccess("ok");
    }
}