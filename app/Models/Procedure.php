<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    protected $fillable = ['name','pdate','result','note', 'uuid', 'user_uuid'];  

}
