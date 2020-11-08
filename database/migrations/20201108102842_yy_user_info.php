<?php

use think\migration\Migrator;
use think\migration\db\Column;

class YyUserInfo extends Migrator
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
        $table = $this->table("user_info");
        $table->addColumn("nickname",'string',['comment'=>'用户昵称','limit'=>30,'default'=>''])
            ->addColumn("uid",'integer',['comment'=>'用户信息的外键','default'=>0])
            ->addColumn("signature",'string',['comment'=>'签名','limit'=>255,'default'=>''])
            ->addColumn("age",'boolean',['comment'=>'年龄默认为0是未知','signed'=>true,'limit'=>1,'default'=>0])
            ->addColumn('birthday','datetime',['comment'=>'生日'])
            ->addColumn('gender','enum',['comment'=>'性别','values'=>['未知','男','女'],'default'=>'未知'])
            ->addTimestamps('created_at','updated_at')
            ->addForeignKey('uid','users')
            ->create();
    }
}
