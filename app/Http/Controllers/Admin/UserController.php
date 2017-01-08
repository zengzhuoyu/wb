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
	public function index(){

        		$this->data['data'] = User::select('user.id','user.lock','user.registime','userinfo.username','userinfo.face50 as face','userinfo.follow','userinfo.fans','userinfo.wb')
	                    ->leftJoin('userinfo', 'user.id', '=', 'userinfo.uid')         
	                    ->orderBy('id','desc')                              
	                    ->paginate(10);

		return view('admin.user.index',$this->data);
	}

}
