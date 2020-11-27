<?php

use think\migration\Migrator;
use think\migration\db\Column;

class YyMenus extends Migrator
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
        $table->addColumn('title','string',['limit'=>30,'comment'=>'菜单名称'])
            ->addColumn('uid','integer',['default'=>0,'comment'=>'菜单的创建者'])
            ->addColumn('component','string',['limit'=>50,'comment'=>'菜单的组件地址'])
            ->addColumn('redirect','string',['limit'=>50,'default'=>'','comment'=>'重定向地址，不需要就不填写'])
            ->addColumn('meta_icon','string',['limit'=>39,'default'=>'','comment'=>'图标'])
            ->addColumn('meta_hidden','boolean',['limit'=>1,'default'=>1,'comment'=>'当前菜单是否需要显示在侧边栏0为显示，1为隐藏'])
            // 当你一个路由下面的 children 声明的路由大于1个时，自动会变成嵌套的模式--如组件页面
            // 只有一个时，会将那个子路由当做根路由显示在侧边栏--如引导页面
            // 若你想不管路由下面的 children 声明的个数，总是显示你的根路由
            // 你可以设置 alwaysShow: true，这样它就会忽略之前定义的规则，一直显示根路由 (默认 false)
            ->addColumn('meta_always_show','boolean',['limit'=>1,'default'=>0,'comment'=>'只有一个子菜单的时候是否合并0为合并1为不合并'])
            ->addColumn('meta_breadcrumb','boolean',['limit'=>1,'default'=>1,'comment'=>'是否显示面包屑0为隐藏1为显示'])
            ->addColumn('meta_cache','boolean',['limit'=>1,'default'=>0,'comment'=>'是否缓存当前的路由界面0为不缓存，1为缓存'])
            ->addColumn('meta_affix','boolean',['limit'=>1,'default'=>0,'comment'=>'当前的路由是否固定在tags-view最前面，默认为0不固定'])
            ->addColumn('meta_active_menu','string',['limit'=>30,'default'=>'','comment'=>'侧边栏高亮指向的菜单，默认指向路由，如果设置当前的值，默认指向为当前的值'])
            ->addForeignKey('uid','users')
            ->addTimestamps('created_at','updated_at')
            ->create();
    }
}
