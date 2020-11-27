<?php
/**
 * @time 2020/11/9 10:17 下午
 * @author aibayanyu
 */

namespace app\model\mysql;


use app\common\exception\ApiException;
use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\Model;

class Users extends BaseModel
{
    public function userinfo()
    {
        return $this->hasOne("UserInfo", "uid", "id");
    }

    public function roles()
    {
        return $this->hasMany("UserRoles", "uid", 'id');
    }

    /**
     * 加密密码的解密功能
     * @time 2020/11/16 9:41 下午
     * @param $password
     * @return string
     * @author aibayanyu
     */
    private function encodePass($password)
    {
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
    public function checkLoginUser()
    {
        $username = request()->param("username");
        $password = request()->param("password");
        $password = $this->encodePass($password);
        // 传递当前用户的username
        $user = $this->where("username", $username)->find();
        if (!$user) apiError("用户信息不存在", 10001);
        // 当前用户信息存在
        if (!password_verify($password, $user->password ?? '')) apiError("密码不正确", 10002);
        $this->where("id", $user->id)
            ->cache(true, $this->cacheTime)
            ->save(['last_login_ip' => request()->ip(), 'last_login_time' => date("Y-m-d H:i:s")]);
        return ['id' => $user->id, 'username' => $username];
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
    public function getUserInfo()
    {
        return $this->where("id", $this->userId)
            ->cache(true, $this->cacheTime)
            ->withoutField("password,updated_at,created_at")->with(['userinfo' => function ($query) {
                $query->withoutField("updated_at,created_at");
            }, 'roles' => function ($query) {
                $query->withoutField('created_at')->with(['role' => function ($q) {
                    $q->field("name,id");
                }]);
            }])->find();
    }

    /**
     * 用户修改密码
     */
    public function getChangePass()
    {
        $oldPassword = request()->param("oldPassword");
        $newPassword = request()->param("newPassword");
        if ($oldPassword === $newPassword) apiError("新密码与旧密码不能相同");
        $user = $this->where("id", $this->userId)
            ->field("password")
            ->find();
        if (!password_verify($oldPassword, $user->password)) apiError("旧密码不正确");
        $password = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->where("id", $this->userId)->save(['password' => $password]);
    }

    /**
     * 获取用户数据列表
     * @time 2020/11/23 7:12 上午
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author aibayanyu
     */
    public function getUserData()
    {
        // 拿到菜单的信息
        [$limit, $page] = $this->getPages();
        // 拿到其他的几个参数
        $username = request()->param("username");
        // 拿到状态
        $status = request()->param("status");
        $where = [];
        if (isset($username)) {
            $where[] = ['username', 'like', '%' . $username . '%'];
        }

        if (isset($status)&&is_numeric($status)) {
            $where[] = ['status', '=', $status];
        }
        $total = $this->where($where)->count();
        // 开始查询数据
        $data = $this->where($where)
            ->withoutField("password,created_at")
            ->order("created_at","DESC")
            ->with(['roles' => function ($query) {
                $query->withoutField('created_at')->with(['role' => function ($q) {
                    $q->field("name,id");
                }]);
            }])
            ->page($page, $limit)->select();
        return [$data, $total];
    }

    /**
     * 根据账号判断当前的账号是否已被使用
     * @time 2020/11/25 8:30 上午
     * @param $username
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author aibayanyu
     */
    private function checkUser($username){
        $user = $this->where('username',$username)->find();
        if ($user){
            return $user->id;
        }
        return  false;
    }

    /**
     * 创建新的用户
     */
    public function createUsers()
    {
        $field = ['username','password','mobile','email','status'];
        $fields = request()->only($field);
        $roles = request()->param('role');
        // 判断当前的账号是否已经存在
        if ($this->checkUser($fields['username'])) apiError("当前账号已存在");
        // 创建当前的用户，并指定当前用户的菜单权限
        $fields['password'] = password_hash($fields['password'],PASSWORD_DEFAULT);
        $this->startTrans();
        try {
            $fields['last_login_time'] = date("Y-m-d H:i:s");
            $fields['last_login_ip'] = request()->ip();
            $fields['expire_time'] = '2099-01-01 00:00:00';
            $id = $this->insertGetId($fields);
            $rolesData  = [];
            foreach ($roles as $role){
                $rolesData[] = [
                    'rid'=>$role,
                    'uid'=>$id,
                    'created_at'=>date("Y-m-d H:i:s")
                ];
            }
            $userinfo['nickname'] = $fields['username'];
            $userinfo['birthday'] = date("Y-m-d H:i:s");
            $userinfo['uid'] = $id;
            $this->roles()->insertAll($rolesData);
            $this->userinfo()->insert($userinfo);
            $this->commit();
        }catch (\Exception $exception){
            $this->rollback();
            apiError($exception->getMessage());
        }
    }

    /**
     * 更新用户的信息
     * @param integer $id
     * @throws ApiException
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function updateUsers(int $id){
        // 判断当前要更新的数据
        $username = request()->param("username");
        $save =[];
        $checkUsername =$this->checkUser($username);
        if ($username&&$checkUsername){
            if ($checkUsername != $id) apiError("新账号已被使用");
        }
        else $save['username'] = $username;
        // 判断当前是否需要更新密码
        $password = request()->param('password');
        if (!empty($password)) $save['password'] = $password;
        $field = ['status','email','mobile'];
        foreach ($field as $value){
            $res = request()->param($value);
            if ($res || $res ==0){
                $save[$value] = $res;
            }
        }
        $roles = request()->param('role');
        // 拿到后台中的权限信息
        $oldRoles = $this->roles()->where('uid',$id)->column('rid');
        // 对比权限信息是否发生变化
        // 拿到相同权限的数据
        $commonRoles = array_intersect($roles,$oldRoles);
        // 拿到需要新增的权限的信息
        $newRoles = array_diff($roles,$commonRoles);
        // 拿到需要删除的权限的信息
        $delRoles = array_diff($oldRoles,$commonRoles);
        // 开始操作数据
        $this->startTrans();
        try {
            $this->where('id',$id)->update($save);
            if (!empty($newRoles)){
                $rolesData  = [];
                foreach ($newRoles as $role){
                    $rolesData[] = [
                        'rid'=>$role,
                        'uid'=>$id,
                        'created_at'=>date("Y-m-d H:i:s")
                    ];
                }
                $this->roles()->insertAll($rolesData);
            }
            if (!empty($delRoles)){
                $this->roles()->where('uid',$id)->whereIn('rid',$delRoles)->delete();
            }
            $this->commit();
        }catch (\Exception $exception) {
            $this->rollback();
            apiError($exception->getMessage());
        }
    }

    /**
     * @time 2020/11/27 7:19 上午
     * @param int $id
     * @return bool
     * @author aibayanyu
     */
    public function deleteUser(int $id){
        $this->startTrans();
        try {
            $this->userinfo()->where("uid",$id)->delete();
            $this->roles()->where("uid",$id)->delete();
            $this->where("id",$id)->delete();
            $this->commit();
            return true;
        }catch (Exception $exception){
            $this->rollback();
            return false;
        }
    }
}