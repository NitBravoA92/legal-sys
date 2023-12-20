<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_service',
        'name',
        'description',
        'worker_id',
        'indications',
        'image',
        'status'
    ];

    public function product_form(){
        return $this->hasMany(ProductForm::class);
    }

    public function order(){
        return $this->hasMany(Order::class);
    }
}
