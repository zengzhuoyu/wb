<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    protected $table = 'letter';
    public $timestamps = false;
    protected $guarded=[];
}
