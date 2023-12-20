<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'product_id',
        'init_date',
        'end_date',
        'comments'
    ];
    
    public function order_client(){
        return $this->belongsTo(Client::class);
    }

    public function order_product(){
        return $this->belongsTo(Product::class);
    }

    public function order_accounter(){
        return $this->hasOne(OrderAccounters::class);
    }

    public function order_details(){
        return $this->hasMany(OrderDetails::class);
    }

    public function order_validator(){
        return $this->hasOne(OrderValidations::class);
    }

    public function order_status(){
        return $this->hasMany(OrderStatus::class);
    }

    public function documents(){
        return $this->hasMany(Document::class);
    }

    public function order_additional_documents_request(){
        return $this->hasMany(OrderAdditionalDocumentsRequest::class);
    }

    public function order_notifications(){
        return $this->hasMany(OrderNotifications::class);
    }
}
