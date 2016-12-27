<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Group;
use App\Http\Models\Follow;
use App\Http\Models\Userinfo;

use Illuminate\Support\Facades\Cache;

class CommonController extends Controller
{
	//图片上传
	public function upload(Request $request){

		$file = $request->file('Filedata');	    	
		$type = $request->input('type');

		if($file -> isValid()){//检验一下上传的文件是否有效.
		    $entension = $file -> getClientOriginalExtension(); //获取上传文件的后缀
		    $newName = date('Ymd') . mt_rand(100,999) . '.' . $entension;
		    $path = 'uploads/'. $type . '/' . date('y_m_d') . '/'; 
		    $result = $file -> move($path,$newName);
		    $filepath = $path . $newName;
		    return $filepath;
		}
	}


	 //异步创建新分组
	public function addGroup(Request $request){

		$name = $request->input('name');

		if(!$name) return json_encode(['status' => 0, 'msg' => '名称不能为空']);

		if(strlen($name) >= 15) return json_encode(['status' => 0, 'msg' => '名称在15位之内']);

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

	/**
	 * 异步移除关注与粉丝
	 */
	public function delFollow(Request $request){

		$id = $request->input('uid',0);
		$type = $request->input('type',1);

		if(!$request->isMethod('post')) return view('404');//定制错误页面

		$uid = $_SESSION['uid'];

		$where = $type ? ['follow' => $id, 'fans' => $uid] : ['follow' => $uid, 'fans' => $id];

		if (!Follow::where($where) -> delete()) return 0;

		if ($type) {
			Userinfo::where('uid',$uid)->decrement('follow');
			Userinfo::where('uid',$id)->decrement('fans');
		} else {
			Userinfo::where('uid',$uid)->decrement('fans');
			Userinfo::where('uid',$id)->decrement('follow');
		}

		return 1;
		
	}

	/**
	 * 异步轮询推送消息
	 */
	public function getMsg(){

		//75节内容
		// $arr = [
		// 	'status' => 1,
		// 	'total' => 2,
		// 	'type' => 3
		// ];
		// return json_encode($arr);
		
		//76节
		$uid = $_SESSION['uid'];

		$msg = Cache::get('usermsg' . $uid);

		if(!$msg) return json_encode(['status' => 0]);

		// 评论
		if($msg['comment']['status']){
			$msg['comment']['status'] = 0;
			Cache::forever('usermsg' . $uid, $msg);

			return json_encode([
				'status' => 1,
				'total' => $msg['comment']['total'],
				'type' => 1
			]);
		}

		// 私信
		if($msg['letter']['status']){
			$msg['letter']['status'] = 0;
			Cache::forever('usermsg' . $uid, $msg);

			return json_encode([
				'status' => 1,
				'total' => $msg['letter']['total'],
				'type' => 2
			]);
		}

		// @我
		if($msg['atme']['status']){
			$msg['atme']['status'] = 0;
			Cache::forever('usermsg' . $uid, $msg);

			return json_encode([
				'status' => 1,
				'total' => $msg['atme']['total'],
				'type' => 3
			]);
		}				
	}

}
