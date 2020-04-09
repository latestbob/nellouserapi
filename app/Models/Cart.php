<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    const TAX_FEE = 0.05; //percentage of total price
    protected $fillable = ['cart_uuid', 'drug_id', 'quantity', 'price', 'prescription', 'prescribed_by', 'user_id', 'vendor_id'];

    public function order()
    {
        $item = $this->belongsTo('App\Models\Order', 'cart_uuid', 'cart_uuid');
        return $item;
    }

    public function drug()
    {
        return $this->belongsTo('App\Models\PharmacyDrug', 'drug_id', 'id');
    }

    public function vendor() {

        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id');
    }
}
