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
use App\Http\Models\Keep;

class IndexController extends Controller
{
	public function index($gid = 0){
		
		$uids = [];
		$where = [];

		if($gid) {
			$where['gid'] = $gid;
		}else{
			$uid = $_SESSION['uid'];
			$uids =[$uid];
			$where['fans'] = $uid;
		}
		
		$result = Follow::where($where)
			->select('follow')
			->get();

		if($result){
			foreach($result as $v){
				$uids[] = $v['follow'];
			}
		}

		if($uids) $data = (new Wb) -> getWeibo($uids);

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

		return redirect($_SERVER['HTTP_REFERER']);
	
	}

	/**
	 * 评论
	 */
	public function comment(Request $request){

		$content = $request->input('content','');
		$wid = $request->input('wid',0);
		$isturn = $request->input('isturn',0);

		$uid = $_SESSION['uid'];

		//提取评论数据
		$data = [
			'content' => $content,
			'time' => time(),
			'uid' => $uid,
			'wid' => $wid
		];

		if(!Comment::insertGetId($data)) return 'false';

		//被评论的微博评论数加1
		Wb::where('id',$wid)->increment('comment');

		//获得被评论的微博的发布者用户名
		$weibo = Wb::where('id',$wid) -> select('id','uid','content','isturn') -> first();
		$username = Userinfo::where('id',$weibo -> uid) -> select('username') -> first();

		//评论同时转发时处理
		if ($isturn) {
			//读取转发微博ID与内容
			$content = $weibo['isturn'] ? $content . ' // @' . $username -> username . ' : ' . $weibo['content'] : $content;

			//同时转发到微博的数据
			$cons = [
				'content' => $content,
				'isturn' => $weibo['isturn'] ? $weibo['isturn'] : $wid,
				'time' => $data['time'],
				'uid' => $uid
			];

			//被评论的微博的转发数加1
			if (Wb::insertGetId($cons)) Wb::where('id',$weibo -> id)->increment('turn');

			return 1;
		}

		//读取评论用户信息
		$user  = Userinfo::where('uid',$uid) -> select('username','face50 as face') -> first();

		//组合评论样式字符串返回
		$str = '';
		$str .= '<dl class="comment_content">';
		$str .= '<dt><a href="/userInfo/'. $uid .'">';
		$str .= '<img src="';
		if ($user -> face){
			$str .= '/'.$user -> face;
		} else {
			$str .= '/bootstrap/img/noface.gif';
		}
		$str .= '" alt="' . $user -> username . '" width="30" height="30"/>';
        		$str .= '</a></dt><dd>';  
        		$str .= '<a href="/userInfo/'. $uid .'" class="comment_name">';
        		$str .= $user -> username . '</a> : ' . replace_weibo($content);
        		$str .= '&nbsp;&nbsp;( ' . time_format($data['time']) . ' )';
        		$str .= '<div class="reply">';
        		$str .= '<a href="">回复</a>';
		$str .= '</div></dd></dl>';
		
		return $str;

	}	

	/**
	 * 异步获取评论内容
	 */
	public function getComment(Request $request){

		// 测试用才写
		// sleep(1);

		$wid = $request->input('wid',0);
		$page = $request->input('page',1);

		//数据的总条数
		$count = Comment::where('wid',$wid)->count();
		//数据可分的总页数
		$total = ceil($count / 10);

		$skip = $page < 2 ? 0 : ($page - 1) * 10;
		
		$data = Comment::where('comment.wid',$wid)
			 ->select('comment.id','comment.content','comment.wid','comment.time','userinfo.username','userinfo.face50 as face','userinfo.uid')
		            ->leftJoin('userinfo', 'comment.uid', '=', 'userinfo.uid')         
		            ->orderBy('time','desc')	    	            
		            ->skip($skip)
		            ->take(10)         
		            ->get();

		if (!$data) return 'false';

		$str = '';
		foreach ($data as $v) {
			$str .= '<dl class="comment_content">';
			$str .= '<dt><a href="/userInfo/'. $v -> uid .'">';
			$str .= '<img src="';
			if ($v -> face){
				$str .= '/'.$v -> face;
			} else {
				$str .= '/bootstrap/img/noface.gif';
			}
			$str .= '" alt="' . $v -> username . '" width="30" height="30"/>';
	        		$str .= '</a></dt><dd>';  
	        		$str .= '<a href="/userInfo/'. $v -> uid .'" class="comment_name">';
	        		$str .= $v -> username . '</a> : ' . replace_weibo($v -> content);
	        		$str .= '&nbsp;&nbsp;( ' . time_format($v -> time) . ' )';
	        		$str .= '<div class="reply">';
	        		$str .= '<a href="">回复</a>';
			$str .= '</div></dd></dl>';
		}

		if ($total > 1) {
			$str .= '<dl class="comment-page">';

			switch ($page) {
				case $page > 1 && $page < $total :
					$str .= '<dd class="page" page="' . ($page - 1) . '" wid="' . $wid . '">上一页</dd>';
					$str .= '<dd class="page" page="' . ($page + 1) . '" wid="' . $wid . '">下一页</dd>';
					break;

				case $page < $total :
					$str .= '<dd class="page" page="' . ($page + 1) . '" wid="' . $wid . '">下一页</dd>';
					break;

				case $page == $total :
					$str .= '<dd class="page" page="' . ($page - 1) . '" wid="' . $wid . '">上一页</dd>';
					break;
			}

			$str .= '</dl>';
		}

		return $str;

	}	

	/**
	 * 收藏微博
	 */
	public function keep(Request $request){

		$wid = $request->input('wid',0);
		$uid = $_SESSION['uid'];

		//检测用户是否已经收藏该微博
		$where = ['wid' => $wid, 'uid' => $uid];
		if (Keep::where($where) -> first()) return -1;
			
		//添加收藏
		$data = [
			'uid' => $uid,
			'time' => time(),
			'wid' => $wid
		];

		if(!Keep::insertGetId($data)) return 0;

		//收藏成功时对该微博的收藏数+1
		Wb::where('uid',$uid)->increment('keep');
		return 1;
	}	
}
