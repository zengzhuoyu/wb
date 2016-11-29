<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Wb;
use App\Http\Models\Picture;
use App\Http\Models\Userinfo;
use App\Http\Models\Follow;
use App\Http\Models\Comment;

class IndexController extends Controller
{
    public function index(){

    	$uid = $_SESSION['uid'];
    	$uids = [$uid];

    	$result = Follow::where('fans',$uid)
    		->select('follow')
    		->get();

    	if($result){
    		foreach($result as $v){
    			$uids[] = $v['follow'];
    		}
    	}

	$data = Wb::whereIn('wb.uid',$uids)
		 ->select('wb.id','wb.content','wb.isturn','wb.time','wb.turn','wb.keep','wb.comment','wb.uid','userinfo.username','userinfo.face50 as face','picture.max','picture.medium','picture.mini')
	            ->leftJoin('userinfo', 'wb.uid', '=', 'userinfo.uid')         
	            ->leftJoin('picture', 'wb.id', '=', 'picture.wid')
	            ->orderBy('time','desc')	    	                     
	            ->get();

	//重组结果集数组，得到转发微博
	if($data) $data = (new Wb)->getTurn($data);

    	return view('home/index')->with('data',$data);
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
		
		$wid = Wb::insertGetId($data);

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

	/**
	 * 用户个人信息页显示
	 */
	public function userInfo($id){

		p($id);
	}

	/**
	 * 首页微博转发表达提交
	 */
	public function turn(Request $request){

		//转载的原微博ID
		$id = $request->input('id');	
		//转发内容			
		$content = $request->input('content');//这里应该限制connent字数，错误返回ajax	
		//转发的微博所转发的微博的id
		$tid = $request->input('tid',0);

		$becomment = $request->input('becomment');

		$uid = $_SESSION['uid'];

		//提取插入数据
		$data = [
			'content' => $content,
			'isturn' => $tid ? $tid : $id,
			'time' => time(),
			'uid' => $uid
		];
		
		//插入数据至微博表
		// $wid = Wb::insertGetId($data);

		//这里并不是用到ajax返回，return返回语句需要修改
		if(!Wb::insertGetId($data)) return json_encode(['status' => 0, 'msg' => '转发失败,请稍后重试']);

		if ($tid) Wb::where('id',$tid)->increment('turn');
			
		//如果点击了同时评论插入内容到评论表
		if (isset($becomment)) {
			$comment_data = [
				'content' => $content,
				'time' => time(),
				'uid' => $uid,
				'wid' => $id
			];

			//插入评论数据后给原微博评论次数+1
			if (Comment::insertGetId($comment_data)) Wb::where('id',$id)->increment('comment');
		}
			
		//原微博转发数+1
		Wb::where('id',$id)->increment('turn');	

		//用户发布微博数+1
		Userinfo::where('uid',$uid)->increment('wb');	

		return redirect('/');
	
	}
}
