<?php

use think\migration\Migrator;
use think\migration\db\Column;

class YyUserRoles extends Migrator
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
        $table = $this->table("user_roles",['id'=>false,'primary_key'=>['rid','uid']]);
        $table->addColumn("rid",'integer',['comment'=>'权限id'])
            ->addColumn('uid','integer',['comment'=>'用户id'])
            ->addColumn('created_at','datetime',['comment'=>'创建时间'])
            ->create();
    }
}
