<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

require_once 'org/code/Code.class.php';

use App\Http\Models\User;
use App\Http\Models\Userinfo;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

class LoginController extends Controller
{
	//登录
	public function login(){

		return view('home/login');		
	}

	//登录表单提交处理
	public function runLogin(Request $request){

		$account = $request->input('account');
		$pwd = $request->input('pwd');
		$auto = $request->input('auto');

		//组合验证数据
		$data = array(

			'account' => $account,
			'password' => $pwd
		);

		$rules = [

			'account'=>'required|alpha_dash|between:5,17',
			'password'=>'required|alpha_dash|between:5,17',
		];

		$message = [

			'account.required'=>'账号不能为空',
			'account.alpha_dash'=>'账号必须以字母开头,且由字母、数字、下划线组成',
			'account.between'=>'账号在5-17位之间',
			'password.required'=>'密码不能为空',
			'password.alpha_dash'=>'密码必须以字母开头,且由字母、数字、下划线组成',
			'password.between'=>'密码在5-17位之间'	
		];

		$validator = Validator::make($data,$rules,$message);
		
		if(!$validator->passes()){

		    return back()->withErrors($validator);		    
		}	

    		$user = User::where('account',$account)->select('password', 'lock','id')->first();

    		if(!$user || Crypt::decrypt($user -> password) != $pwd){
    			return back() -> with('errors','账号或者密码错误');    			
    		}

    		if($user -> lock){
    			return back() -> with('errors','该账号被锁定');      			
    		}
			
		//处理下一次自动登录
		if (isset($auto)) {

			$ip = $request->getClientIp();
			$value = $account . '|' . $ip;
			$value = encryption($value);

			// Cookie::queue('auto', $value, Config::get('config.AUTO_LOGIN_TIME')); X
			setcookie("auto", $value, Config::get('config.AUTO_LOGIN_TIME'));

		}

            	//插入数据成功后把用户ID写SESSION
    		// session(['uid' => $user -> id]); X
    		$_SESSION['uid'] = $user -> id;

            	//跳转至首页
    		return redirect('/');			
	}

	//注册
	public function register(){

		return view('home/register');		
	}	

	//注册表单提交处理
	public function runRegis(Request $request){

		$verify = $request->input('verify');	
		$pwd = $request->input('pwd');
		$pwded = $request->input('password_confirmation');
		$account = $request->input('account');
		$uname = $request->input('uname');

    		//验证验证码
    		$code = new \Code();
    		$_code = $code -> get();

    		if(strtoupper($verify) != $_code){
    			return back() -> with('errors','验证码错误');
    		}

		//组合验证数据
		$data = array(

			'account' => $account,
			'password' => $pwd,
			'password_confirmation' => $pwded,
			'username' => $uname
		);

		$rules = [

			'account'=>'required|alpha_dash|between:5,17|unique:user',
			'password'=>'required|alpha_dash|between:5,17|confirmed',
			'username'=>'required|between:2,10|unique:userinfo'
		];

		$message = [

			'account.required'=>'账号不能为空',
			'account.alpha_dash'=>'账号必须以字母开头,且由字母、数字、下划线组成',
			'account.between'=>'账号在5-17位之间',
			'account.unique'=>'账号已存在',
			'password.required'=>'密码不能为空',
			'password.alpha_dash'=>'密码必须以字母开头,且由字母、数字、下划线组成',
			'password.between'=>'密码在5-17位之间',			
			'password.confirmed'=>'两次密码不一致',			
			'username.required'=>'昵称不能为空',			
			'username.between'=>'昵称在2-10位之间',			
			'username.unique'=>'昵称已存在'		
		];

		$validator = Validator::make($data,$rules,$message);
		
		if(!$validator->passes()){

		    return back()->withErrors($validator);		    
		}		

		//组合user数据
		$user_data = array(

			'account' => $account,
			'password' => Crypt::encrypt($pwd),
			'registime' => $_SERVER['REQUEST_TIME'],

		);

	           $user_id = User::insertGetId($user_data);	

		//组合userinfo数据
            	$userinfo_data = array(

            		'uid' => $user_id,
            		'username' => $uname
            	);

            	$userinfo_id = Userinfo::insertGetId($userinfo_data);	     

            	if(!$user_id || !$userinfo_id){

    			return back() -> with('errors','注册失败,请稍后重试');            		
            	}

            	//插入数据成功后把用户ID写SESSION
    		$_SESSION['uid'] = $user_id;

            	//跳转至首页
    		return redirect('/');            	

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
