<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['todo_name','image_url',	'director_comment','customer_comment','designer_programmer_comment','status','comments','step_id'
];

    public function step()
    {
       return $this->belongsTo('App\Step');
    }

    public function product()
    {
       return $this->belongsTo('App\Product');
    }

    public function comments()
    {
       return $this->hasMany('App\Comment');
    }

}
