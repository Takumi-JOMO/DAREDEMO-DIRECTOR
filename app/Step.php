<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = ['product_id','director_schedule_start_date','director_schedule_end_date','customer_schedule_start_date','customer_schedule_end_date','designer_programmer_schedule_start_date','designer_programmer_schedule_end_date'];

    public function todos()
    {
       return $this->hasMany('App\Todo');
    //    hasManyの後ろのTodoはモデル名
    }

    public function product()
    {
       return $this->belongsTo('App\Product');
    }

}
