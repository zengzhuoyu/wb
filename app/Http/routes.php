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
 * 自定义的打印便捷函数
 */
function p($arr){

	echo "<pre>";
	print_r($arr);
	die;
}

/**
 * 异位或加密字符串
 * @param  [String]  $value [需要加密的字符串]
 * @param  [integer] $type  [加密解密（0：加密，1：解密）]
 * @return [String]         [加密或解密后的字符串]
 */
function encryption ($value, $type = 0) {

	$key = md5(Config::get('config.ENCTYPTION_KEY'));

	if (!$type) {//加密
		return str_replace('=', '', base64_encode($value ^ $key));
	}

	//解密
	$value = base64_decode($value);
	return $value ^ $key;
}	

/**
 * 前台
 */

Route::get('/login', 'Home\LoginController@login');//登录页面

Route::get('/register', 'Home\LoginController@register');//注册页面

Route::get('/getVerify', 'Home\LoginController@getVerify');//获得验证码

Route::post('/checkAccount', 'Home\LoginController@checkAccount');//注册时异步验证账号是否已存在
Route::post('/checkUname', 'Home\LoginController@checkUname');//注册时异步验证昵称是否已存在
Route::post('/checkVerify', 'Home\LoginController@checkVerify');//注册时异步验证验证码是否正确

Route::post('/runRegis', 'Home\LoginController@runRegis');//注册表单提交处理

Route::post('/runLogin', 'Home\LoginController@runLogin');//登录表单提交处理

Route::group(['middleware'=>['home.login'],'namespace'=>'Home'],function(){

	Route::get('/', 'IndexController@index');//前台首页

	Route::get('/quit', 'IndexController@quit');//退出

});