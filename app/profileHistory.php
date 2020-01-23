<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class profileHistory extends Model
{
    //
    
    public static $rules = [
        'profile_id' => 'required',
        'edited_at' => 'required',
        ];
}
