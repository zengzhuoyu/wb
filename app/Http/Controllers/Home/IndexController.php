<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Wb;
use App\Http\Models\Picture;
use App\Http\Models\Userinfo;

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

	/**
	 * 微博发布处理
	 */
	public function sendWeibo(Request $request){

		$content = $request->input('content');
		$max = $request->input('max');
		$medium = $request->input('medium');
		$mini = $request->input('mini');

		if(strlen($content) > 50) return json_encode(['status' => 0, 'msg' => '内容在150字之内']);

		$uid = $_SESSION['uid'];

		$data = [
			'content' => $content,
			'time' => time(),
			'uid' => $uid
		];
		
		$wid = Wb::insert($data);

		if(!$wid) return json_encode(['status' => 0, 'msg' => '发布失败,请稍后重试']);

		if($max){

			$img_data = [
				'max' => $max,
				'medium' => $medium,
				'mini' => $mini,
				'wid' => $wid
			];

			Picture::insert($img_data);
		
		}

		Userinfo::where('uid',$uid)->increment('wb');

		return json_encode(['status' => 1, 'msg' => '发布成功']);			

	}    
}
