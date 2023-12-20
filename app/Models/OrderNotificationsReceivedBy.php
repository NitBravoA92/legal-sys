<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderNotificationsReceivedBy extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_notifications_id',
        'worker_id'
    ];

    public function order_notification(){
        return $this->belongsTo(OrderNotifications::class);
    }

    public function worker(){
        return $this->belongsTo(Worker::class);
    }
}
