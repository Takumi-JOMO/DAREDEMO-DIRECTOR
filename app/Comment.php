<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id','todo_id','body'
];
    public function todo()
    {
       return $this->belongsTo('App\Todo');
    }
    public function user()
    {
       return $this->belongsTo('App\User');
    }
}
