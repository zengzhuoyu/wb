<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
}
