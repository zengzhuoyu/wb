<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $table = 'picture';
    public $timestamps = false;
    protected $guarded=[];
}
