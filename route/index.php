<?php
/**
 * @time 2020/10/31 10:24 下午
 * @author aibayanyu
 */

use think\facade\Route;

/**
 * index的全局路由
 */
Route::group("",function (){
    Route::rule("/","index.Index/index");
});