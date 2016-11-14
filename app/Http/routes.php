<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * 前台
 */

Route::any('/login', 'Home\LoginController@login');//登录页面

Route::any('/register', 'Home\LoginController@register');//注册页面