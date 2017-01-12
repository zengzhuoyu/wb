<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Userinfo;
use App\Http\Models\User;

class UserController extends Controller
{

	/*
	* 用户列表
	 */
	public function index(Request $request){

		$this->data['keyword'] = $request->input('keyword','');
		$this->data['type'] = $request->input('type',1);

		$this->data['all'] = $request->input('all',0);

		if($this->data['keyword'] && !($this->data['all'])){

			switch($this->data['type']){
				case 0:
					$field = 'username';
					break;
				case 1:
					$field = 'id';				
					break;
			}

	        		$this->data['data'] = User::where('userinfo.'.$field,'like','%'.$this->data['keyword'].'%')->select('userinfo.id','user.lock','user.registime','userinfo.username','userinfo.face50 as face','userinfo.follow','userinfo.fans','userinfo.wb')
		                    ->leftJoin('userinfo', 'user.id', '=', 'userinfo.uid')         
		                    ->orderBy('id','desc')                              
		                    ->paginate(10);			
		}else{

	        		$this->data['data'] = User::select('userinfo.id','user.lock','user.registime','userinfo.username','userinfo.face50 as face','userinfo.follow','userinfo.fans','userinfo.wb')
		                    ->leftJoin('userinfo', 'user.id', '=', 'userinfo.uid')         
		                    ->orderBy('id','desc')                              
		                    ->paginate(10);			
		}

		return view('admin.user.index',$this->data);
	}

	/**
	 * 异步锁定、解锁用户
	 */
	public function lockUser(Request $request){

		$uid = $request->input('uid',0);
		$data['lock'] = $request->input('lock',0);

		$msg = $data['lock'] ? '锁定' : '解锁';

		if(!User::where('id',$uid)->update($data)){
			return ['status' => 0, 'msg' => $msg . '失败,请稍候再试'];
		}

		return ['status' => 1, 'msg' => $msg . '成功'];
	}

}
