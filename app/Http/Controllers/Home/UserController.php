<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Userinfo;
use App\Http\Models\User;
use App\Http\Models\Wb;
use App\Http\Models\Follow;
use App\Http\Models\Keep;
use App\Http\Models\Letter;
use App\Http\Models\Comment;
use App\Http\Models\Atme;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{

	/*
	* 用户基本信息设置视图
	 */
	public function index(){

		$uid = $_SESSION['uid'];

    		$user = Userinfo::where('uid',$uid)
    			-> select('username', 'truename', 'sex', 'location', 'constellation', 'intro', 'face180')
    			-> first();

		return view('home/userset')->with('user',$user);
	}

	/**
	 * 修改用户基本信息
	 */
	Public function editBasic(Request $request){

		$nickname = $request->input('nickname');		
		$truename = $request->input('truename');		
		$sex = $request->input('sex');	
		$province = $request->input('province');		
		$city = $request->input('city');		
		$night = $request->input('night');		
		$intro = $request->input('intro');		

		$data = array(
			'username' => $nickname,
			'truename' => $truename,
			'sex' => $sex,
			'location' => $province . ' ' . $city,
			'constellation' => $night,
			'intro' => $intro
		);

		$uid = $_SESSION['uid'];

	           $result = Userinfo::where('uid',$uid) 
	           	-> update($data);			

		if(!$result){
    			return back() -> with('errors','修改失败,请稍后重试');			
		}
    		
    		return back() -> with('errors','修改成功');			
				
	}	

	/**
	 * 修改用户头像
	 */
	Public function editFace(Request $request){

		$face50 = $request->input('face50');		
		$face80 = $request->input('face80');		
		$face180 = $request->input('face180');	

		$uid = $_SESSION['uid'];

		$data = array(
			'face50' => $face50,
			'face80' => $face80,
			'face180' => $face180
		);

    		$oldFace = Userinfo::where('uid',$uid)
    			-> select('face50', 'face80','face180')
    			-> first();

	           $result = Userinfo::where('uid',$uid)
	           	 -> update($data);			

		if(!$result){
    			return back() -> with('errors','修改失败,请稍后重试');			
		}
    		
    		if($oldFace -> face180){
			@unlink($oldFace -> face50);    		
			@unlink($oldFace -> face80);    		
			@unlink($oldFace -> face180);      			
    		}
  		
    		return back() -> with('errors','修改成功');

	}	

	/**
	 * 修改密码
	 */
	Public function editPwd(Request $request){

		$old = $request->input('old');		
		$new = $request->input('new');		
		$newed = $request->input('password_confirmation');

		$uid = $_SESSION['uid'];

		//组合验证数据
		$data = array(
			'password' => $new,
			'password_confirmation' => $newed
		);

		$rules = [
			'password'=>'required|alpha_dash|between:5,17|confirmed'
		];

		$message = [
			'password.required'=>'新密码不能为空',
			'password.alpha_dash'=>'新密码必须以字母开头,且由字母、数字、下划线组成',
			'password.between'=>'新密码在5-17位之间',
			'password.confirmed'=>'两次密码不一致'
		];

		$validator = Validator::make($data,$rules,$message);
		
		if(!$validator->passes()){

		    return back()->withErrors($validator);		    
		}

		//验证旧密码	
    		$user = User::where('id',$uid)
    			-> first();

    		if(Crypt::decrypt($user -> password) != $old){
    			return back() -> with('errors','旧密码错误');    			
    		}

		$user -> password = Crypt::encrypt($new);

		if(!$user -> update()){
    			return back() -> with('errors','修改失败,请稍后重试');			
		}
    		  		
    		return back() -> with('errors','修改成功');

	}

	//用户个人信息页
	public function userInfo($user){

		$userinfo = Userinfo::where('uid',$user)->first();//用户id过来
		if(!$userinfo){
			$userinfo = Userinfo::where('username',$user)->first();//用户名过来

			if(!$userinfo) return redirect('/');//这里应该是一个错误页面提示：用户不存在，正在为您跳转至首页
		}
		$id = $userinfo -> uid;

		$uids = [$id];
		$data = (new Wb) -> getWeibo($uids);

		//我的关注
		if (Cache::has('follow_' . $id)) {//判断缓存是否存在
			$follow = Cache::get('follow_' . $id);//读取缓存
		}else{
			$follow = Follow::where('fans',$id) -> select('follow') -> get();
			foreach($follow as $k => $v){
				$follow[$k] = $v['follow'];
			}
			$follow = Userinfo::whereIn('uid',$follow) -> select('username','face50 as face','uid') -> take(8) -> get();

			Cache::put('follow_' . $id, $follow, 60);//写入缓存	
		}

		//我的粉丝
		if (Cache::has('fans_' . $id)) {
			$fans = Cache::get('fans_' . $id);
		}else{		
			$fans = Follow::where('follow',$id) -> select('fans') -> get();
			foreach($fans as $k => $v){
				$fans[$k] = $v['fans'];
			}
			$fans = Userinfo::whereIn('uid',$fans) -> select('username','face50 as face','uid') -> take(8) -> get();

			Cache::put('fans_' . $id, $fans, 60);
		}

		return view('home/userinfo')
			 -> with('userinfo',$userinfo)
			 -> with('id',$id)
			 -> with('data',$data)
			 -> with('follow',$follow)
			 -> with('fans',$fans);
	}	

	/**
	 * 用户关注列表
	 */
	public function follow(Request $request,$id){

		$type = 1;
		$results = $this -> _ff($request,$id,$type);

		if($results == 'false') return view('404');

		return view('home/fflist')
			->with('users',$results['users'])
			->with('follow',$results['follow'])
			->with('fans',$results['fans'])
			->with('type',$type);

	}

	/**
	 * 用户粉丝列表
	 */
	public function fans(Request $request,$id){

		$type = 0;
		$results = $this -> _ff($request,$id,$type);

		if($results == 'false') return view('404');
		
		return view('home/fflist')
			->with('users',$results['users'])
			->with('follow',$results['follow'])
			->with('fans',$results['fans'])
			->with('type',$type);	
	}		

	private function _ff($request,$id,$type){

		$user = User::where('id',$id) -> first();

		//判断请求过来的方式
		if(!$request->isMethod('get') || !$user) return 'false';//定制错误页面

		$where = $type ? 'fans' : 'follow' ;
		$field = $type ? 'follow' : 'fans' ;
		$uids = Follow::where($where,$id) -> select($field) -> get();		

		if($uids){

			foreach ($uids as $k => $v) {
				$uids[$k] = $v[$field];
			}			

			$field = ['face50 as face','username','sex','location','follow','fans','wb','uid'];

			$users = Userinfo::whereIn('uid',$uids) -> select($field) -> paginate(10);
		}	

		$results['users'] = $users;

		$uid = $_SESSION['uid'];

		//当前登录用户的关注表
		$follow = Follow::where('fans',$uid) -> select('follow') -> get() -> toArray();
		if ($follow) {
			foreach ($follow as $k => $v) {
				$follow[$k] = $v['follow'];
			}
		}
		$results['follow'] = $follow;

		//当前登录用户的粉丝表
		$fans = Follow::where('follow',$uid) -> select('fans') -> get() -> toArray();
		if ($fans) {
			foreach ($fans as $k => $v) {
				$fans[$k] = $v['fans'];
			}
		}
		$results['fans'] = $fans;	

		return $results;	
	}

	/**
	 * 我的收藏列表
	 */
	public function keep(){

		$uid = $_SESSION['uid'];

		$data = Keep::where('keep.uid',$uid)
			 ->select('keep.id as kid','keep.time as ktime','wb.id','wb.content','wb.isturn','wb.time','wb.turn','wb.comment','wb.uid','picture.mini', 'picture.medium', 'picture.max','userinfo.username', 'userinfo.face50 as face')
		            ->leftJoin('wb', 'keep.wid', '=', 'wb.id')         
		            ->leftJoin('picture', 'wb.id', '=', 'picture.wid')	
		            ->leftJoin('userinfo', 'wb.uid', '=', 'userinfo.uid')		            	            
		            ->orderBy('ktime','desc')	    	                  
		            ->paginate(10);

		if($data) $data = (new Wb) -> getTurn($data);

		return view('home/atkeeplist') -> with('data',$data);	            

	}

	/**
	 * 异步取消收藏
	 */
	public function cancelKeep(Request $request){

		$kid = (int) $request->input('kid',0);
		$wid = (int) $request->input('wid',0);

		$wb = Wb::where('id',$wid) -> first();

		if (!$kid || !$wid || !$wb || !Keep::where('id',$kid) -> delete()) return 0;

		Wb::where('id',$wid) -> decrement('keep');

		return 1;
	}	

	/**
	 * 我的私信列表
	 */
	public function letter(){

		$uid = $_SESSION['uid'];

		//写入消息推送
		set_msg($uid, 2, true);
		
		$data = Letter::where('letter.uid',$uid)
			 ->select('letter.id','letter.content','letter.time','userinfo.username','userinfo.face50 as face','userinfo.uid')
		            ->leftJoin('userinfo', 'letter.from', '=', 'userinfo.uid')         	            	            
		            ->orderBy('time','desc')	    	                  
		            ->paginate(10);

		return view('home/letter') -> with('data',$data);
	}

	/**
	 * 私信发送表单处理
	 */
	public function letterSend(Request $request){

		$name = $request->input('name','');
		$content = $request->input('content','');

		$user = Userinfo::where('username',$name)-> select('uid') -> first();

		if (!$user) return back() -> with('errors','该用户不存在');
			
		$data = [
			'from' => $_SESSION['uid'],
			'content' => $content,
			'time' => time(),
			'uid' => $user -> uid
			];

		if (!Letter::insertGetId($data)) return back() -> with('errors','发送失败请重试...');

		//写入消息推送
		set_msg($user -> uid, 2);

		return back() -> with('errors','信息已发送');
	}	

	/**
	 * 异步删除私信
	 */
	public function delLetter(Request $request){

		$lid = (int) $request->input('lid',0);

		if (!Letter::where('id',$lid) -> delete()) return 0;
		
		return 1;

	}	

	/**
	 * 评论列表
	 */
	public function comment(){

		//写入消息推送
		set_msg($_SESSION['uid'], 1, true);

		$data = Comment::where('comment.uid',$_SESSION['uid'])
			 ->select('comment.id','comment.content','comment.wid','comment.time','userinfo.username','userinfo.face50 as face','userinfo.uid')
		            ->leftJoin('userinfo', 'comment.uid', '=', 'userinfo.uid')         
		            ->orderBy('time','desc')	    	                  
		            ->paginate(10);
		
		return view('home/comment') -> with('data',$data);
	}	

	/**
	 * 评论回复
	 */
	public function reply(Request $request){

		$wid = (int) $request->input('wid',0);
		$content = $request->input('content','');

		$data = [
			'content' => $content,
			'time' => time(),
			'uid' => $_SESSION['uid'],
			'wid' => $wid
			];

		if(!Comment::insertGetId($data)) return 0;

		Wb::where('id',$wid) -> increment('comment');

		return 1;
	}	

	/**
	 * 删除评论
	 */
	public function delComment(Request $request){

		$cid = (int) $request->input('cid',0);
		$wid = (int) $request->input('wid',0);

		if (!Comment::where('id',$cid) -> delete()) return 0;

		Wb::where('id',$wid)->decrement('comment');

		return 1;
	}	


	/**
	 * @提到我的
	 */
	public function atme(){

		//写入消息推送
		set_msg($_SESSION['uid'], 3, true);

		$wids = Atme::where('uid',$_SESSION['uid']) -> select('wid') -> get();

		if ($wids){
			foreach ($wids as $k => $v) {
				$wids[$k] = $v['wid'];
			}
		}

        		$data = Wb::whereIn('wb.id',$wids)//whereIn 使用的值必须是一个索引数组
	                    ->select('wb.id','wb.content','wb.isturn','wb.time','wb.turn','wb.keep','wb.comment','wb.uid','userinfo.username','userinfo.face50 as face','picture.max','picture.medium','picture.mini')
	                    ->leftJoin('userinfo', 'wb.uid', '=', 'userinfo.uid')         
	                    ->leftJoin('picture', 'wb.id', '=', 'picture.wid')
	                    ->orderBy('time','desc')                              
	                    ->paginate(10);

		if($data) $data = (new Wb) -> getTurn($data);

		$atme = 1;
		
		return view('home/atkeeplist') -> with('atme',$atme) -> with('data',$data);
	}

}
