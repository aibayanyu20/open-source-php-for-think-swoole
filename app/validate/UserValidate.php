<?php
/**
 * @time 2020/11/9 7:49 上午
 * @author aibayanyu
 */

namespace app\validate;


use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'username|账号'=>'require|length:5,20',
        'password|密码'=>'require'
    ];

    protected $message = [
        'username.length'=>'账号不能低于5位且不能大于20位'
    ];

    protected $scene = [
        'login'=>['username','password']
    ];
}