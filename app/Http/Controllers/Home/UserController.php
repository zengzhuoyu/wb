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
	public function userInfo($id){

		$userinfo = Userinfo::where('uid',$id)->first();

		if(!$userinfo) return redirect('/');//这里应该是一个错误页面提示：用户不存在，正在为您跳转至首页

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
}
