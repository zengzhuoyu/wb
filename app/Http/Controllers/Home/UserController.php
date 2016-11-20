<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Userinfo;

class UserController extends Controller
{

	/*
	* 用户基本信息设置视图
	 */
	public function index(){

		$uid = $_SESSION['uid'];

    		$user = Userinfo::where('uid',$uid)->select('username', 'truename', 'sex', 'location', 'constellation', 'intro', 'face180')->first();
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

	           $result = Userinfo::where('uid',$uid) -> update($data);			

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

    		$oldFace = Userinfo::where('uid',$uid)->select('face50', 'face80','face180')->first();

	           $result = Userinfo::where('uid',$uid) -> update($data);			

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
}
