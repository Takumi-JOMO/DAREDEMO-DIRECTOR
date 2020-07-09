<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matter extends Model
{
    protected $fillable = ['name', 'outputs', 'status' ,'comments'];
    
    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();//多対多の場合のみTimestampsが必要
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function steps()
    {
        return $this->hasMany('App\Step');
    }
}