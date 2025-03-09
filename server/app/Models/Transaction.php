<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'type', 'category', 'amount', 'description', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
