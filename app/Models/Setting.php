<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'app_name',
        'app_owner',
        'app_address',
        'app_email',
        'app_phone',
        'app_logo',
        'about_us',
        'language'
    ];
}
