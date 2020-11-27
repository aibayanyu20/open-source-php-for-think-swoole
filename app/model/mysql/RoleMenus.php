<?php
/**
 * @time 2020/11/19 10:03 下午
 * @author aibayanyu
 */

namespace app\model\mysql;


class RoleMenus extends BaseModel
{
    public function roles(){
        return $this->belongsTo("Roles","rid","id");
    }
}