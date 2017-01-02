<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

require_once 'org/code/Code.class.php';

class LoginController extends Controller
{
    //登录页面
    public function login(){

    	return view('admin.login');
    }

    //在登录页面显示验证图案
    public function getCode(){

    	$code = new \Code();
    	$code -> make();
    }    
}
