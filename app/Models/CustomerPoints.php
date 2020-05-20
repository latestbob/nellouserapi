<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPoints extends Model
{
    protected $fillable = ['point', 'total_points_earned_today', 'customer_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'customer_id', 'id');
    }

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
    }
}
