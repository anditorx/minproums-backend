<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuaItemCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code', 'user_email', 'product_price','qty',
        'status','total'
    ];
    
}
