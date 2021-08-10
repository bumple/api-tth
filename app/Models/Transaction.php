<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function checkTranByUser(){
        return $this->category->wallet->user->id === Auth::id();
    }

}
