<?php
/**
 * @time 2020/11/9 10:17 下午
 * @author aibayanyu
 */

namespace app\model\mysql;


use app\common\exception\ApiException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Model;

class Users extends BaseModel
{
    public function userinfo(){
        return $this->hasOne("UserInfo","uid","id");
    }

    /**
     * 加密密码的解密功能
     * @time 2020/11/16 9:41 下午
     * @param $password
     * @return string
     * @author aibayanyu
     */
    private function encodePass($password){
        return $password;
    }

    /**
     * 登录操作
     * @time 2020/11/16 9:39 下午
     * @return array|false|Model
     * @throws ApiException
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author aibayanyu
     */
    public function checkLoginUser(){
        $username = request()->param("username");
        $password = request()->param("password");
        $password = $this->encodePass($password);
        // 传递当前用户的username
        $user = $this->where("username",$username)->find();
        if (!$user) apiError("用户信息不存在",10001);
        // 当前用户信息存在
        if (!password_verify($password,$user->password??'')) apiError("密码不正确",10002);
        $this->where("id",$user->id)
            ->cache(true,$this->cacheTime)
            ->save(['last_login_ip'=>request()->ip(),'last_login_time'=>date("Y-m-d H:i:s")]);
        return  ['id'=>$user->id,'username'=>$username];
    }

    /**
     * 获取用户的基本信息
     * @time 2020/11/17 8:56 下午
     * @return array|Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author aibayanyu
     */
    public function getUserInfo(){
        return $this->where("id",$this->userId)
            ->cache(true,$this->cacheTime)
            ->withoutField("password,updated_at,created_at")->with(['userinfo'=>function($query){
                $query->withoutField("updated_at,created_at");
            }])->find();
    }

    /**
     * 用户修改密码
     */
    public function getChangePass(){
        $oldPassword = request()->param("oldPassword");
        $newPassword = request()->param("newPassword");
        if ($oldPassword === $newPassword) apiError("新密码与旧密码不能相同");
        $user = $this->where("id",$this->userId)
            ->field("password")
            ->find();
        if (!password_verify($oldPassword,$user->password)) apiError("旧密码不正确");
        $password = password_hash($newPassword,PASSWORD_DEFAULT);
        $this->where("id",$this->userId)->save(['password'=>$password]);
    }
}