<?php
/**
 * @time 2020/10/31 10:58 下午
 * @author aibayanyu
 */

namespace app\model\mysql;


use think\Model;

class BaseModel extends Model
{
    public function __construct(array $data = [])
    {
        $this->userId = request()->userId;
        parent::__construct($data);
    }

    protected $cacheTime = 3600 * 6;

    protected $userId;

    protected $autoWriteTimestamp = true;
    /**
     * 创建时间
     * @var string
     */
    protected $createTime = "created_at";

    /**
     * 更新时间
     * @var string
     */
    protected $updateTime = "updated_at";

    /**
     * 获取用户的权限的信息
     * @time 2020/11/20 7:11 上午
     * @return array
     * @author aibayanyu
     */
    public function getRoles(): array{
        // 拿到用户对应的权限的信息
        $rolesModel = new Roles();
        $userRoles = new UserRoles();
        $rids = $userRoles->where("uid",$this->userId)->column("rid");
        $roles = $rolesModel->whereIn("id",$rids)->column("name");
        // 判断当前是否为超级管理员
        $isAdmin = false;
        if (in_array("admin",$roles)){
            $isAdmin = true;
        }
        return ['ids'=>$rids,'roles'=>$roles,'isAdmin'=>$isAdmin];
    }

    protected function getPages(){
        // 获取菜单的limit和page
        $limit = request()->param("limit");
        // 获取菜单的页数
        $page = request()->param("page");
        if (empty($limit)) $limit = 20;
        if (empty($page)) $page = 1;
        return [$limit,$page];
    }
}