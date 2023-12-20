<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderValidations extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'worker_id',
        'status'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function validator(){
        return $this->belongsTo(Worker::class);
    }
}
