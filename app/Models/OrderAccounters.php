<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAccounters extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'worker_id'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function accounter(){
        return $this->belongsTo(Worker::class);
    }
}
