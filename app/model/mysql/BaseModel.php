<?php
/**
 * @time 2020/10/31 10:58 下午
 * @author aibayanyu
 */

namespace app\model\mysql;


use think\Model;

class BaseModel extends Model
{
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
}