<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['title', 'url', 'matter_id'];

    public function matter()
    {
        
       return $this->belongsTo('App\Matter');
    }

    public function steps()
    {
        return $this->hasMany('App\Step');
    }
}
