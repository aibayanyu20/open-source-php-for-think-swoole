<?php
/**
 * @time 2020/11/16 9:32 下午
 * @author aibayanyu
 */

namespace app\validate;


use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck($scene = ""){
        // 判断当前是否场景验证
        $params = $this->request->param();
        if (empty($scene)){
            // 当前不是场景验证
            $valid = $this->check($params);
        }else{
            // 当前是场景验证
            $valid = $this->scene($scene)->check($params);
        }
        // 判断当前场景验证是否可以通过场景验证标识20000
        if (!$valid) apiError($this->getError(),20000);
        return true;
    }
}