<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
	//登录
	public function login(){

		return view('home/login');		
	}

	//登录
	public function register(){

		return view('home/register');		
	}	
}
