<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'invoice',
        'product_price', 'total', 'status'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'code', 'product_code');
    }
    
    public function getCreatedAtAttribute($value) 
    {
        return Carbon::parse($value)->timestamp;
    }
    
    public function getUpdatedAtAttribute($value) 
    {
        return Carbon::parse($value)->timestamp;
    }
}
