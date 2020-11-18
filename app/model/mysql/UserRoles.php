<?php
/**
 * @time 2020/11/18 7:49 上午
 * @author aibayanyu
 */

namespace app\model\mysql;


class UserRoles extends BaseModel
{
    public function rule(){
        return $this->belongsTo("Roles","rid","id");
    }
}