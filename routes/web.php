<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//前置中间件
Route::middleware('Login')->group(function () {
    Route::get('upload','myshop\IndexController@upload'); // 上传
    Route::post('do_upload','myshop\IndexController@do_upload'); // 上传
});

//前置中间件 时间
Route::middleware('time')->group(function () {
    Route::get('edit','myshop\IndexController@edit'); // 修改
    Route::post('update','myshop\IndexController@update'); // 修改
});

Route::get('register','myshop\LoginController@register'); //注册
Route::post('do_register','myshop\LoginController@do_register'); //注册表单验证
Route::get('login','myshop\LoginController@login'); //登录
Route::post('do_login','myshop\LoginController@do_login'); //登录表单提交验证
Route::get('logut','myshop\LoginController@register'); // 退出
Route::get('create','myshop\IndexController@create'); // 添加
Route::post('save','myshop\IndexController@save'); // 执行添加
Route::get('index','myshop\IndexController@index'); // 列表
Route::get('delete','myshop\IndexController@delete'); // 删除



