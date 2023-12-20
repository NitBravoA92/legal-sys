<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAdditionalDocumentsRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'worker_id',
        'fieldname_english',
        'fieldname_spanish',
        'field_type',
        'status'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function requester(){
        return $this->belongsTo(Worker::class);
    }
}
