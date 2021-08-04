<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function wallet()
    {
        return $this->hasOneThrough(Wallet::class,Category::class);
    }
}
