<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'client_id',
        'worker_id'
    ];

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function call_center(){
        return $this->belongsTo(Worker::class);
    }
}
