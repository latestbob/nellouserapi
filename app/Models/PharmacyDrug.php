<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyDrug extends Model
{
    protected $table = 'pharmacy_drugs';
    protected $primaryKey = 'id';

    protected $fillable = ['brand', 'name', 'description', 'price', 'require_prescription','dosage_type', 'image', 'uuid', 'vendor_id', 'status', 'quantity'];

    protected $appends = ['is_out_of_stock'];

    public function category() {
        return $this->belongsTo('App\Models\DrugCategory', 'category_id', 'id');
    }

    public function getIsOutOfStockAttribute() {
        if ($this->getAttribute('quantity') <= 0) return true;
        return $this->getAttribute('status') == false;
    }
}
