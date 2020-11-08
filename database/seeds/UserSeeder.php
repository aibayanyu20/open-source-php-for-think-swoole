<?php

use think\migration\Seeder;

class UserSeeder extends Seeder
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
        $users = $this->table("users");
        $adminInfo = [
            [
                'username' => 'admin',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'last_login_time'=>date('Y-m-d H:i:s'),
                'expire_time'=>date('2099-12-12')
            ]
        ];
        $users->insert($adminInfo)->save();
    }
}