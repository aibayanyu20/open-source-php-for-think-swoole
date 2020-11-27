<?php
/**
 * @time 2020/11/19 9:13 下午
 * @author aibayanyu
 */

namespace app\controller\api\v1;


use app\BaseController;
use app\model\mysql\Menus;

class Menu extends BaseController
{
    public function getMenus(Menus $menus){
        $menusArr = $menus->getMenus();
        return apiDataSuccess($menusArr);
    }

    public function getMenusData(Menus $menus){
        $menusArr = $menus->getMenusData();
        return apiDataSuccess($menusArr);
    }
}