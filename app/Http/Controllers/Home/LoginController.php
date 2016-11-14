<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

require_once 'org/code/Code.class.php';

use App\Http\Models\User;
use App\Http\Models\Userinfo;

class LoginController extends Controller
{
	//登录
	public function login(){

		return view('home/login');		
	}

	//注册
	public function register(){

		return view('home/register');		
	}	

	//获得验证码
	public function getVerify(){

	    	$code = new \Code();
	    	$code -> make();
	}

	/**
	 * 注册时异步验证账号是否已存在
	 */
	public function checkAccount(Request $request){

		$account = $request->input('account');
		
    		$user = User::where('account',$account)->first();		

		if($user){
			echo 'false';
		}else{
			echo 'true';
		}
	}	

	/**
	 * 注册时异步验证昵称是否已存在
	 */
	public function checkUname(Request $request){

		$username = $request->input('uname');
		
    		$user = Userinfo::where('username',$username)->first();		

		if($user){
			echo 'false';
		}else{
			echo 'true';
		}
	}		

	/**
	 * 注册时异步验证验证码是否正确
	 */
	public function checkVerify(Request $request){

		$verify = $request->input('verify');	
		
    		//验证验证码
    		$code = new \Code();
    		$_code = $code -> get();

    		if(strtoupper($verify) != $_code){
			echo "false";
    		}else{
			echo "true";    			
    		}
	}	
}
