<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    public function products(){
        return $this->belongsToMany(Product::class)->withPivot('count');
    }
    public function factor(){
        return $this->belongsTo(Factor::class);
    }
}
