<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Userinfo;
use App\Http\Models\Follow;
use App\Http\Models\Group;
use App\Http\Models\Wb;

class SearchController extends Controller
{
	/**
	 * 搜索微博
	 */
	public function searchWeibo(Request $request){

		$k = $request->input('k','');
		
		$data = null;

		if ($k) {

			$data = Wb::where('content','like','%'.$k.'%')
		                    ->select('wb.id','wb.content','wb.isturn','wb.time','wb.turn','wb.keep','wb.comment','wb.uid','userinfo.username','userinfo.face50 as face','picture.max','picture.medium','picture.mini')
		                    ->leftJoin('userinfo', 'wb.uid', '=', 'userinfo.uid')         
		                    ->leftJoin('picture', 'wb.id', '=', 'picture.wid')
		                    ->orderBy('time','desc')                              
		                    ->paginate(10);
		}

		if($data) $data = (new Wb) -> getTurn($data);

		$type = 1;//菜单栏搜索微博而不是找人的标记
		return view('home/searchweibo',compact('k','data','type'));	
	}

	/**
	 * 搜索找人
	 * 检索出除自己外呢称含有关键字的用户
	 */
	public function searchUser(Request $request){

		$k = $request->input('k');

		$uid = $_SESSION['uid'];

		$field = ['username', 'sex', 'location', 'intro', 'face180', 'follow', 'fans', 'wb', 'uid'];

		if($k){
			$data = Userinfo::where('uid','<>',$uid)
			->where('username','like','%'.$k.'%')
			->select($field)
			->paginate(10);
		}else{
			$data = Userinfo::where('uid','<>',$uid)
			->select($field)
			->paginate(10);		
		}

		$data = $this->_getMutual($data,$uid);
		
		return view('home/searchuser',compact('k','data'));
	}

	private function _getMutual($data,$uid){

		if(!$data) return false;

		foreach($data as $k => $v){

			$resulti = Follow::select('follow')
				->where('follow',$v['uid'])
				->where('fans',$uid);

			$resultii = Follow::select('follow')
				->where('follow',$uid)
				->where('fans',$v['uid'])
				->union($resulti)
				->get();

			if(count($resultii) == 2){
				$data[$k]['mutual'] = 1;
				$data[$k]['followed'] = 1;
			}else{
				$data[$k]['mutual'] = 0;

				$data[$k]['followed'] = Follow::where('follow',$v['uid'])
					->where('fans',$uid)
					->count();
			}
		}

		return $data;
	}

	//获得自己建过的分组
	public function getGroup(){

		$data = Group::where('uid',$_SESSION['uid'])
			->get();

		if($data) return json_encode(['status' => 1,'data' => $data]);		
	}
}
