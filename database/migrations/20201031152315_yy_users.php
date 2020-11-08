<?php

use think\migration\Migrator;
use think\migration\db\Column;

class YyUsers extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table("users");
        $table->addColumn("username",'string',['limit'=>30,'comment'=>'登录账号'])
            ->addColumn("password",'string',['limit'=>64,'comment'=>'登录密码'])
            ->addColumn("status",'boolean',['default'=>1,'limit'=>1,'comment'=>'0为禁用，1为账号正常'])
            ->addColumn('mobile','string',['default'=>'','limit'=>12,'comment'=>'手机号'])
            ->addColumn('email','string',['default'=>'','limit'=>30,'comment'=>'邮箱'])
            ->addColumn('pid','integer',['default'=>0,'comment'=>'父级账号信息'])
            ->addColumn('last_login_ip','string',['default'=>'','comment'=>'最后登录IP','limit'=>30])
            ->addColumn('last_login_time','datetime',['comment'=>'最后登录时间'])
            ->addColumn('expire_time','datetime',['comment'=>'过期时间'])
            ->addTimestamps('created_at','updated_at')
            ->addIndex(['username'],['unique'=>true])
            ->create();
    }
}
