<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'fieldname_english',
        'fieldname_spanish',
        'field_type',
    ];
    
    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function details(){
        return $this->hasOne(OrderDetails::class);
    }
    
}
