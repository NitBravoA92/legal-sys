<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'productForm_id',
        'data'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }
    
    public function product_form(){
        return $this->belongsTo(ProductForm::class);
    }

    public function document_details(){
        return $this->hasOne(DocumentDetails::class);
    }
}
