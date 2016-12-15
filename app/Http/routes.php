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
 * 格式化时间
 * @param  [type] $time [要格式化的时间戳]
 * @return [type]       [description]
 */
function time_format($time){
	//当前时间
	$now = time();
	//今天零时零分零秒
	$today = strtotime(date('y-m-d', $now));
	//传递时间与当前时秒相差的秒数
	$diff = $now - $time;
	$str = '';
	switch ($time) {
		case $diff < 60 :
			$str = $diff . '秒前';
			break;
		case $diff < 3600 :
			$str = floor($diff / 60) . '分钟前';
			break;
		case $diff < (3600 * 8) :
			$str = floor($diff / 3600) . '小时前';
			break;
		case $time > $today :
			$str = '今天&nbsp;' . date('H:i', $time);
			break;
		default : 
			$str = date('Y-m-d H:i:s', $time);
	}
	return $str;
}

/**
 * 替换微博内容的URL地址、@用户与表情
 * @param  [String] $content [需要处理的微博字符串]
 * @return [String]          [处理完成后的字符串]
 */
function replace_weibo($content){

	if (empty($content)) return;

	//给URL地址加上 <a> 链接
	$preg = '/(?:http:\/\/)?([\w.]+[\w\/]*\.[\w.]+[\w\/]*\??[\w=\&\+\%]*)/is';
	$content = preg_replace($preg, '<a href="http://\\1" target="_blank">\\1</a>', $content);
	
	//给@用户加是 <a> 链接
	$preg = '/@(\S+)\s/is';
	// $content = preg_replace($preg, '<a href="' . __APP__ . '/User/\\1">@\\1</a>', $content);
	$content = preg_replace($preg, '<a href="/userInfo/\\1">@\\1</a>', $content);
	
	// //提取微博内容中所有表情文件
	// $preg = '/\[(\S+?)\]/is';
	// preg_match_all($preg, $content, $arr);
	// //载入表情包数组文件
	// $phiz = include './Public/Data/phiz.php';
	// if (!empty($arr[1])) {
	// 	foreach ($arr[1] as $k => $v) {
	// 		$name = array_search($v, $phiz);
	// 		if ($name) {
	// 			$content = str_replace($arr[0][$k], '<img src="' . __ROOT__ . '/PUBLIC/Images/phiz/' . $name . '.gif" title="' . $v . '"/>', $content);
	// 		}
	// 	}
	// }
	// return str_replace(C('FILTER'), '***', $content);
	
	return $content;
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

	Route::get('/userSet', 'UserController@index');//个人设置页面

	Route::post('/editBasic', 'UserController@editBasic');//个人设置：基本信息修改表单提交处理

	Route::post('/uploadFace', 'CommonController@upload');//个人设置：头像上传

	Route::post('/editFace', 'UserController@editFace');//个人设置：头像上传表单提交

	Route::post('/editPwd', 'UserController@editPwd');//个人设置：修改密码表单提交

	Route::get('/searchUser', 'SearchController@searchUser');//搜索：找人	

	Route::post('/addGroup', 'CommonController@addGroup');//创建新分组	

	Route::post('/getGroup', 'SearchController@getGroup');//+ 关注时获取自己建过的分组

	Route::post('/addFollow', 'CommonController@addFollow');//+ 关注

	Route::post('/sendWeibo', 'IndexController@sendWeibo');//首页发微博表单提交

	// Route::get('/userInfo/{id}', 'UserController@userInfo')->where('id', '[0-9]+');//用户个人信息页
	Route::get('/userInfo/{user}', 'UserController@userInfo');//用户个人信息页

	Route::post('/turn', 'IndexController@turn');//首页微博转发表单提交

	Route::post('/comment', 'IndexController@comment');//首页微博评论表单提交

	Route::post('/getComment', 'IndexController@getComment');//首页微博获得评论信息

	Route::get('/{gid}', 'IndexController@index')->where('gid', '[0-9]+');//首页自建分组 - 点击分组，相应微博显示在首页

	Route::post('/keep', 'IndexController@keep');//首页收藏功能

	Route::post('/delWeibo', 'IndexController@delWeibo');//首页、用户个人信息页功能

	Route::get('/follow/{id}','UserController@follow')->where('id', '[0-9]+');//用户关注列表

	Route::get('/fans/{id}','UserController@fans')->where('id', '[0-9]+');//用户粉丝列表

	Route::post('/delFollow','CommonController@delFollow');//移除关注与粉丝

	Route::get('/keep','UserController@keep');//我的收藏列表

	Route::post('/cancelKeep','UserController@cancelKeep');//我的收藏列表:异步取消收藏

	Route::get('/letter','UserController@letter');//我的私信列表

	Route::post('/letterSend','UserController@letterSend');//我的私信列表：发送私信表单提交

	Route::post('/delLetter','UserController@delLetter');//我的私信列表：私信删除

	Route::get('/comment','UserController@comment');//我的评论列表

	Route::post('/reply','UserController@reply');//我的评论列表：回复表单提交

	Route::post('/delComment','UserController@delComment');//我的评论列表：回复删除

});