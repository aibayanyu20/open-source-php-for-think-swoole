<?php
/**
 * @time 2020/10/31 10:25 下午
 * @author aibayanyu
 */
use think\facade\Route;

Route::group("/api/:v",function (){
    Route::rule("/","api.:v.Index/index");
});