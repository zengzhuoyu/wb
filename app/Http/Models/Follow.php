<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = 'follow';
    public $timestamps = false;
    protected $guarded=[];
}
