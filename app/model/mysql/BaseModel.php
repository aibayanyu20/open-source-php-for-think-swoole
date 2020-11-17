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
}