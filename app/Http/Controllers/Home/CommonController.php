<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Group;
use App\Http\Models\Follow;
use App\Http\Models\Userinfo;

class CommonController extends Controller
{
	//图片上传
	public function upload(Request $request){

		$file = $request->file('Filedata');	    	
		$type = $request->input('type');

		if($file -> isValid()){//检验一下上传的文件是否有效.
		    $entension = $file -> getClientOriginalExtension(); //获取上传文件的后缀
		    $newName = date('Ymd') . mt_rand(100,999) . '.' . $entension;
		    $path = 'uploads/'. $type . '/' . date('Y_d') . '/'; 
		    $result = $file -> move($path,$newName);
		    $filepath = $path . $newName;
		    return $filepath;
		}
	}


	 //异步创建新分组
	public function addGroup(Request $request){

		$name = $request->input('name');

		if(!$name) return json_encode(['status' => 0, 'msg' => '名称不能为空']);

		if(strlen($name) >= 15) return json_encode(['status' => 0, 'msg' => '名称在15位以内']);

		$data = [
			'name' => $name,
			'uid' => $_SESSION['uid']//谁创建的
		];

	           $id = Group::insertGetId($data);

		if(!$id){
			return json_encode(['status' => 0, 'msg' => '创建失败,请稍后重试']);		
			//用echo的话,下面的代码会继续执行	
		}

		return json_encode(['status' => 1, 'msg' => '创建成功']);		
	}

	/**
	 * 异步添加关注
	 */
	Public function addFollow(Request $request){

		$follow = $request->input('follow');
		$gid = $request->input('gid');

		$data = [
			'follow' => $follow,
			'fans' => $_SESSION['uid'],
			'gid' => $gid
		];

		if(!Follow::insert($data)) return json_encode(['status' => 0, 'msg' => '关注失败,请稍后重试']);

		Userinfo::where('uid',$follow)->increment('fans');
		Userinfo::where('uid',$_SESSION['uid'])->increment('follow');

		$result = Follow::where('fans',$follow)
		->where('follow',$_SESSION['uid'])
		->first();

		if($result) return json_encode(['status' => 1, 'msg' => '关注成功','mutual' => 1]);

		return json_encode(['status' => 1, 'msg' => '关注成功','mutual' => 0]);	
	}	
}
