<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

use App\Http\Models\Userinfo;
use App\Http\Models\Picture;

class Wb extends Model
{
    protected $table = 'wb';
    public $timestamps = false;
    protected $guarded=[];

    public function getWeibo($uids){

        $data = $this->whereIn('wb.uid',$uids)//whereIn 使用的值必须是一个索引数组
                    ->select('wb.id','wb.content','wb.isturn','wb.time','wb.turn','wb.keep','wb.comment','wb.uid','userinfo.username','userinfo.face50 as face','picture.max','picture.medium','picture.mini')
                    ->leftJoin('userinfo', 'wb.uid', '=', 'userinfo.uid')         
                    ->leftJoin('picture', 'wb.id', '=', 'picture.wid')
                    ->orderBy('time','desc')                              
                    ->paginate(10);

        if($data) $this -> _getTurn($data);

        return $data;       
    }

    //重组结果集数组，得到转发微博
    private function _getTurn($data){

    	foreach($data as $k => $v){
    		if($v -> isturn){
    			$v -> isturn = $this->where('wb.id',$v -> isturn)
                                    ->select('userinfo.username','wb.id','wb.content','wb.uid','wb.time','wb.turn','wb.comment','picture.max','picture.medium','picture.mini')
                                    ->leftJoin('userinfo','wb.uid','=','userinfo.uid')
                                    ->leftJoin('picture','wb.id','=','picture.wid')
                                    ->first();
    		}

    	}
    	
    	return $data;
    }
}
