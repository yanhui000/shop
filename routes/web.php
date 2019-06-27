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
    return view('home/index');
});

//后台前置中间件
Route::middleware('Login')->group(function () {
    Route::get('upload','myshop\IndexController@upload'); // 上传
    Route::post('do_upload','myshop\IndexController@do_upload'); // 上传
});

//前台前置中间件
Route::middleware('Home')->group(function () {
    Route::get('wish','home\Index@wish'); // 商品详情页
    Route::get('buyCart','home\Index@buyCart'); // 加入购物车
    Route::get('do_buyCart','home\Index@do_buyCart'); // 加入购物车
    Route::get('order','home\Index@order'); // 订单
    Route::get('do_order','home\Index@do_order'); // 订单
});

//前置中间件 时间
// Route::middleware('time')->group(function () {
//     Route::get('edit','myshop\IndexController@edit'); // 修改
//     Route::post('update','myshop\IndexController@update'); // 修改
// });

//后台
Route::get('register','myshop\LoginController@register'); //注册
Route::post('do_register','myshop\LoginController@do_register'); //注册表单验证
Route::get('login','myshop\LoginController@login'); //登录
Route::post('do_login','myshop\LoginController@do_login'); //登录表单提交验证
Route::get('logout','myshop\LoginController@logout'); // 退出
Route::get('create','myshop\IndexController@create'); // 添加
Route::post('save','myshop\IndexController@save'); // 执行添加
Route::get('index','myshop\IndexController@index'); // 列表
Route::get('delete','myshop\IndexController@delete'); // 删除
Route::get('edit','myshop\IndexController@edit'); // 修改
Route::post('update','myshop\IndexController@update'); // 修改

//前台
Route::get('home_index','home\Index@index'); // 列表
Route::get('home_register','home\Login@register'); //注册
Route::post('home_do_register','home\Login@do_register'); //注册表单验证
Route::get('home_login','home\Login@login'); // 登录
Route::post('home_do_login','home\Login@do_login'); // 执行登录
Route::get('home_logout','home\Login@logout'); // 退出
Route::get('pay','Pay\AliPayController@pay'); // 支付宝支付
Route::get('return_url','home\Index@return_url'); // 支付宝同步回调
Route::get('asynUrl','home\Index@asynUrl'); // 支付宝同步回调





