<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Authority extends Model
{
    public function users()
    {
        return $this->hasMany('App\User');
        // ('App\User')はAppフォルダのUser.php
    }
}
