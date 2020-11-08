<?php

use think\migration\Seeder;

class UserInfoSeeder extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $userInfo = $this->table('user_info');
        $sql = <<<SQL
        SELECT id from `yy_users` WHERE `username` = 'admin'
SQL;
        $users = $this->fetchRow($sql);
        $data = [
            [
                'nickname'=>'管理员',
                'uid'=>$users['id'],
                'birthday'=>date('Y-m-d H:i:s')
            ]
        ];
        $userInfo->insert($data)->save();
    }
}