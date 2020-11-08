<?php

use think\migration\Migrator;
use think\migration\db\Column;

class YyRoles extends Migrator
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
        $table = $this->table('roles');
        $table->addColumn('name','string',['limit'=>20,'comment'=>'权限标识'])
            ->addColumn('title','string',['limit'=>30,'comment'=>'权限标题'])
            ->addColumn('status','boolean',['limit'=>1,'default'=>1,'comment'=>'是否启用当前的权限'])
            ->addColumn('uid','integer',['comment'=>'创建当前权限的用户id'])
            ->addColumn('sort','integer',['comment'=>'权限显示的排序','default'=>0,'limit'=>10])
            ->addForeignKey('uid','users')
            ->addTimestamps("created_at",'updated_at')
            ->create();
    }
}
