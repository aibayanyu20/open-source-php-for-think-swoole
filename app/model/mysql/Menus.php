<?php
/**
 * @time 2020/11/19 10:03 下午
 * @author aibayanyu
 */

namespace app\model\mysql;


use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Menus extends BaseModel
{
    public function roles(){
        return $this->hasMany("RoleMenus","rid","id");
    }

    /**
     * 获取授权的菜单信息
     * @time 2020/11/22 6:35 下午
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author aibayanyu
     */
    public function getMenus(){
        // 判断当前的是什么用户，如果是普通用户，就需要按权限拿菜单，如果是超级管理员就直接拿到菜单的信息
        $rolesInfo = $this->getRoles();
        // 获取菜单的详情信息
        if ($rolesInfo['isAdmin']){
            // 拿到当前用户的基本信息
            $menus = $this->where("status",1)
                ->order("order","DESC")
                ->withoutField("created_at,updated_at")
                ->select();
        }else{
            // 根据当前roleId拿到菜单的id
            $menuIds = (new RoleMenus())->whereIn("rid",$rolesInfo['ids'])
                ->column("id");
            $menuIds = array_unique($menuIds);
            $menus = $this->where("status",1)
                ->order("order","DESC")
                ->with(['roles'=>function($query){
                    $query->field("rid,name");
                }])
                ->withoutField("created_at,updated_at")
                ->whereIn("id",$menuIds)
                ->select();
        }
        return $this->formatMenus($menus->toArray(),$rolesInfo['roles'],$rolesInfo['isAdmin']);
    }

    /**
     * 格式化菜单的信息
     * @time 2020/11/22 6:35 下午
     * @param $menus
     * @param $roles
     * @param false $isAdmin
     * @return array
     * @author aibayanyu
     */
    private function formatMenus($menus,$roles,$isAdmin = false){
        $newMenus = [];
        foreach ($menus as $item=>$menu){
            foreach ($menu as $key=>$value){
                $newMenus[$item][camelize($key)] = $value;
            }
            if ($isAdmin){
                $newMenus[$item]['roles'] = $roles;
            }else{
                $newMenus[$item]['roles'] = $menu['roles'];
            }
        }
        return $newMenus;
    }

    /**
     * 生成树状的菜单
     * @time 2020/11/22 6:35 下午
     * @author aibayanyu
     */
    public function getMenusData(){
        return [];
    }
}