<?php
/**
 * @time 2020/10/31 10:34 下午
 * @author aibayanyu
 */

namespace app\controller\index;


use app\BaseController;

class Index extends BaseController
{
    public function index(){
        $arr1 = [2,4,5];
        $arr2 = [2,3,4,5];
        halt(array_diff($arr2,$arr1));
        sleep(5);
    }
}