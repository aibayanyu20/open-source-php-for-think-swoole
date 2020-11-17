<?php
/**
 * @time 2020/10/31 10:25 下午
 * @author aibayanyu
 */
use think\facade\Route;

Route::group("/api/:v",function (){
    Route::rule("/","api.:v.Index/index");

    /**
     * 授权访问
     */
    Route::group("/",function (){
        Route::get("getUserInfo","api.:v.User/getUserInfo");
        Route::post("user/changePass",'api.:v.User/changePass');
    })->middleware(['checkAuth']);

    /**
     * 登录
     */
    Route::group("/",function (){
        Route::post("login","api.:v.User/login");
    });
});