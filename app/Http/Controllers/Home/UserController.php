<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Userinfo;
use App\Http\Models\User;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

	/*
	* 用户基本信息设置视图
	 */
	public function index(){

		$uid = $_SESSION['uid'];

    		$user = Userinfo::where('uid',$uid)
    			-> select('username', 'truename', 'sex', 'location', 'constellation', 'intro', 'face180')
    			-> first();

		return view('home/user')->with('user',$user);
	}

	/**
	 * 修改用户基本信息
	 */
	Public function editBasic(Request $request){

		$nickname = $request->input('nickname');		
		$truename = $request->input('truename');		
		$sex = $request->input('sex');	
		$province = $request->input('province');		
		$city = $request->input('city');		
		$night = $request->input('night');		
		$intro = $request->input('intro');		

		$data = array(
			'username' => $nickname,
			'truename' => $truename,
			'sex' => $sex,
			'location' => $province . ' ' . $city,
			'constellation' => $night,
			'intro' => $intro
		);

		$uid = $_SESSION['uid'];

	           $result = Userinfo::where('uid',$uid) 
	           	-> update($data);			

		if(!$result){
    			return back() -> with('errors','修改失败,请稍后重试');			
		}
    		
    		return back() -> with('errors','修改成功');			
				
	}	

	/**
	 * 修改用户头像
	 */
	Public function editFace(Request $request){

		$face50 = $request->input('face50');		
		$face80 = $request->input('face80');		
		$face180 = $request->input('face180');	

		$uid = $_SESSION['uid'];

		$data = array(
			'face50' => $face50,
			'face80' => $face80,
			'face180' => $face180
		);

    		$oldFace = Userinfo::where('uid',$uid)
    			-> select('face50', 'face80','face180')
    			-> first();

	           $result = Userinfo::where('uid',$uid)
	           	 -> update($data);			

		if(!$result){
    			return back() -> with('errors','修改失败,请稍后重试');			
		}
    		
    		if($oldFace -> face180){
			@unlink($oldFace -> face50);    		
			@unlink($oldFace -> face80);    		
			@unlink($oldFace -> face180);      			
    		}
  		
    		return back() -> with('errors','修改成功');

	}	

	/**
	 * 修改密码
	 */
	Public function editPwd(Request $request){

		$old = $request->input('old');		
		$new = $request->input('new');		
		$newed = $request->input('password_confirmation');

		$uid = $_SESSION['uid'];

		//组合验证数据
		$data = array(
			'password' => $new,
			'password_confirmation' => $newed
		);

		$rules = [
			'password'=>'required|alpha_dash|between:5,17|confirmed'
		];

		$message = [
			'password.required'=>'新密码不能为空',
			'password.alpha_dash'=>'新密码必须以字母开头,且由字母、数字、下划线组成',
			'password.between'=>'新密码在5-17位之间',
			'password.confirmed'=>'两次密码不一致'
		];

		$validator = Validator::make($data,$rules,$message);
		
		if(!$validator->passes()){

		    return back()->withErrors($validator);		    
		}

		//验证旧密码	
    		$user = User::where('id',$uid)
    			-> first();

    		if(Crypt::decrypt($user -> password) != $old){
    			return back() -> with('errors','旧密码错误');    			
    		}

		$user -> password = Crypt::encrypt($new);

		if(!$user -> update()){
    			return back() -> with('errors','修改失败,请稍后重试');			
		}
    		  		
    		return back() -> with('errors','修改成功');

	}	
}
