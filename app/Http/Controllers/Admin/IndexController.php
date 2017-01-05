<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\User;
use App\Http\Models\Wb;
use App\Http\Models\Comment;

class IndexController extends Controller
{
	public function index(){

		return view('admin.index');
	}

	public function info(){

		// $user = User::count();
		// $lock = User::where('lock',1)->count();

		// $wb = Wb::where('isturn',0)->count();
		// $isturn = Wb::where('isturn','>',0)->count();
		// $comment = comment::count();

		// return view('admin.info')
		// 	->with('user',$user)
		// 	->with('lock',$lock)
		// 	->with('wb',$wb)
		// 	->with('isturn',$isturn)
		// 	->with('comment',$comment);
			
		//以上简便写法			
		$this->data['user'] = User::count();
		$this->data['lock'] = User::where('lock',1)->count();

		$this->data['wb'] = Wb::where('isturn',0)->count();
		$this->data['isturn'] = Wb::where('isturn','>',0)->count();
		$this->data['comment'] = comment::count();
		
		return view('admin.info',$this->data);		
	}

	//退出
	public function loginOut(){

		session_unset();
		session_destroy();		

		return redirect('admin/login');
	}	    
}
