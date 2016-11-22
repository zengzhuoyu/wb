<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Userinfo;
use App\Http\Models\Follow;

class SearchController extends Controller
{
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
}
