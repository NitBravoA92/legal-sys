<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppNotifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_short',
        'data_long',
        'read_at',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
}
