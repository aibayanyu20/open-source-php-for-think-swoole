<?php

use think\migration\Migrator;
use think\migration\db\Column;

class UpdateYyMenus extends Migrator
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
        $table = $this->table("menus");
        $table->addColumn('type','boolean',[
            'limit'=>1,'comment'=>'当前的菜单的类型0为目录1为菜单','default'=>1,'after'=>'uid'])
            ->addColumn('pid','integer',['default'=>0,'comment'=>'当前的菜单的父级id','after'=>'type'])
            ->update();
    }
}
