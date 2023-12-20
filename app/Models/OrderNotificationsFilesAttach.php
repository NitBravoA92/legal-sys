<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderNotificationsFilesAttach extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_notifications_id',
        'document_id'
    ];

    public function order_notification(){
        return $this->belongsTo(OrderNotifications::class);
    }

    public function document(){
        return $this->belongsTo(Document::class);
    }

}
