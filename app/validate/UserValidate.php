<?php
/**
 * @time 2020/11/9 7:49 上午
 * @author aibayanyu
 */

namespace app\validate;


class UserValidate extends BaseValidate
{
    protected $rule = [
        'username|账号'=>'require|length:5,20',
        'password|密码'=>'require|min:6',
        'oldPassword|旧密码'=>'require|min:6',
        'newPassword|新密码'=>'require|min:6',
        'rePassword|确认密码'=>'require|min:6|confirm:newPassword'
    ];

    protected $message = [
        'username.length'=>'账号不能低于5位且不能大于20位',
        'password.min'=>'密码不能低于6位',
        'oldPassword.min'=>'旧密码不能低于6位',
        'newPassword.min'=>'新密码不能低于6位',
        'rePassword.min'=>'确认密码不能低于6位',
        'rePassword.confirm'=>'确认密码与新密码不一致'
    ];

    protected $scene = [
        'login'=>['username','password'],
        'update'=>['oldPassword','newPassword','rePassword']
    ];
}