<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageAnalysis extends Model
{
    protected $fillable = [
        'user_id', 'image_path', 'result'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //
}
