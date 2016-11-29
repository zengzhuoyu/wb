<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Wb extends Model
{
    protected $table = 'wb';
    public $timestamps = false;
    protected $guarded=[];

    public function getTurn($data){

    	foreach($data as $k => $v){
    		if($v -> isturn){
    			$v -> isturn = $this
                                    ->where('wb.id',$v -> isturn)
                                    ->select('userinfo.username','wb.id','wb.content','wb.uid','wb.time','wb.turn','wb.comment','picture.max','picture.medium','picture.mini')
                                    ->leftJoin('userinfo','wb.uid','=','userinfo.uid')
                                    ->leftJoin('picture','wb.id','=','picture.wid')
                                    ->first();
    		}

    	}
    	
    	return $data;
    }
}
