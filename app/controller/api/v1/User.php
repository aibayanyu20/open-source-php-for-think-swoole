<?php
/**
 * @time 2020/11/9 7:49 上午
 * @author aibayanyu
 */

namespace app\controller\api\v1;


use app\BaseController;
use app\common\exception\ApiException;
use app\common\lib\jwt\JwtCheckAuth;
use app\model\mysql\Roles;
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

    /**
     * 退出登录
     * @time 2020/11/19 9:12 下午
     * @return Json
     * @author aibayanyu
     */
    public function logout(){
        // 拿到当前用户的token取消当前用户的token
        Cache::delete($this->request->token);
        return apiSuccess("退出成功");
    }

    /**
     * 获取数据
     * @param Users $users
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function index(Users $users){
        [$data,$total] = $users->getUserData();
        return apiDataSuccess(['list'=>$data,'total'=>$total]);
    }

    /**
     * 获取权限
     * @param Roles $roles
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getRoles(Roles $roles){
        $res = $roles->where("status",1)
            ->field("id,title")->select();
        return apiDataSuccess($res);
    }

    /**
     * 资源路由创建用户
     * @time 2020/11/25 8:22 上午
     * @param Users $users
     * @param UserValidate $userValidate
     * @return Json
     * @author aibayanyu
     */
    public function save(Users $users,UserValidate $userValidate){
        // 校验数据
        $userValidate->goCheck('save');
        $users->createUsers();
       return apiSuccess('ok');
    }

    /**
     * 更新用户的信息
     * @time 2020/11/26 8:06 上午
     * @param $id
     * @param Users $users
     * @return Json
     * @throws ApiException
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author aibayanyu
     */
    public function edit($id,Users $users){
        $users->updateUsers($id);
        return apiSuccess("ok");
    }

    /**
     * 删除用户信息
     * @time 2020/11/27 7:17 上午
     * @param $id
     * @param Users $users
     * @return Json
     * @throws ApiException
     * @author aibayanyu
     */
    public function delete($id,Users $users){
        // 拿到用户的id开始进行删除操作
        $res = $users->deleteUser($id);
        if (!$res) apiError("用户删除失败");
        return apiSuccess("删除成功");
    }
}