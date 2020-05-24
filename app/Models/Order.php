<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'payment_method', 'customer_id', 'cart_uuid',
        'amount', 'firstname', 'lastname', 'email', 'phone',
        'order_ref', 'company', 'address1', 'address2', 'location_id',
        'pickup_location_id', 'city', 'delivery_method',
//        'state',
//        'postal_code',
        'payment_confirmed', 'delivery_status'
    ];

    public function items() {
        return $this->hasMany('App\Models\Cart', 'cart_uuid', 'cart_uuid');
    }

    public function customer()
    {
        return $this->belongsTo('App\User', 'customer_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Locations', 'location_id', 'id');
    }

    public function pickup_location()
    {
        return $this->belongsTo('App\Models\Pharmacies', 'pickup_location_id', 'id');
    }

}
