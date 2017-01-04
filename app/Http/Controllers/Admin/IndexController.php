<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
	public function index(){

		return view('admin.index');
	}

	public function info(){

		return view('admin.info');
	}

	//退出
	public function loginOut(){

		session_unset();
		session_destroy();		

		return redirect('admin/login');
	}	    
}
