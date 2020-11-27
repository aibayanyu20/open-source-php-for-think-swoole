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
        Route::get("getMenus","api.:v.Menu/getMenus");
        Route::get("logout","api.:v.User/logout");
        Route::get("user/getRoles","api.:v.User/getRoles");
        Route::resource('user','api.:v.User');
    })->middleware(['checkAuth']);

    /**
     * 登录
     */
    Route::group("/",function (){
        Route::post("login","api.:v.User/login");
    });
});