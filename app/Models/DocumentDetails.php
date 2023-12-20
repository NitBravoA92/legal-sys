<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'orderDetails_id'
    ];

    public function document(){
        return $this->belongsTo(Document::class);
    }

    public function order_detail(){
        return $this->belongsTo(OrderDetails::class);
    }
}
