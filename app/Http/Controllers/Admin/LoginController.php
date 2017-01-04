<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Admin;

use Illuminate\Support\Facades\Crypt;

require_once 'org/code/Code.class.php';

class LoginController extends Controller
{
    //登录页面
    public function login(){

    	return view('admin.login');
    }

    //在登录页面显示验证图案
    public function getCode(){

    	$code = new \Code();
    	$code -> make();
    }    

    //登录操作
    public function doLogin(Request $request){

	$code = $request->input('code','');
	$user_name = $request->input('user_name','');
	$user_pass = $request->input('user_pass','');

	//验证验证码
	$getCode = new \Code();
	$_code = $getCode -> get();

	if(strtoupper($code) != $_code){
		return back()->with('msg','验证码错误');
	}    	

	//验证用户名和密码
	$admin = Admin::where('username',$user_name)->first();
	if(!$admin || Crypt::decrypt($admin->password) != $user_pass){
		return back()->with('msg','用户名 或者 密码错误');
	}

	if ($admin->lock) {
		return back()->with('msg','账号被锁定');
	}

	$data = [
		'logintime' => time(),
		'loginip' => $request->getClientIp()
	];

	Admin::where('id', $admin->id)->update($data);

	$_SESSION['uid'] = $admin->id;
	$_SESSION['username'] = $admin->username;
	$_SESSION['logintime'] = date('Y-m-d H:i', $admin->logintime);
	$_SESSION['now'] = date('Y-m-d H:i', time());
	$_SESSION['loginip'] = $admin->loginip;
	$_SESSION['admin'] = $admin->admin;

    	return redirect('admin/index');
    }
}
