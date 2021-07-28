<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;
    protected $table = "wallets";

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}