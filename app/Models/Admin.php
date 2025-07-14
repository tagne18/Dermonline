<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Admin extends Model
{
    protected $fillable = [
        'user_id',
        'fonction',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

