<?php

namespace App\http\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comment';
    public $timestamps = false;
    protected $guarded=[];
}
