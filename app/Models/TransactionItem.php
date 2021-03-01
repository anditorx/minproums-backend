<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice', 'product_code', 'qty'
    ];

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'invoice', 'invoice');
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
