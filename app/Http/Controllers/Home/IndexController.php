<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index(){

    	return view('home/index');
    }

    public function quit(){

	//卸载SESSION
	unset($_SESSION['uid']);

	//删除用于自动登录的COOKIE
	//有效时间设置成过期时间，浏览器就会把它删除掉
	if(isset($_COOKIE['auto'])){
		@setcookie('auto', '', time() - 3600);		
	}

	//跳转致登录页
            return redirect('/login');
    	
    }    
}
