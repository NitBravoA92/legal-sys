<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderNotifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'title',
        'type',
        'content',
        'status'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function order_notifications_received_by(){
        return $this->hasOne(OrderNotificationsReceivedBy::class);
    }
}
