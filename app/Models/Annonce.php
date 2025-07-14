<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
   // app/Models/Annonce.php

protected $fillable = [
    'titre',
    'contenu',
    'image',
    'date_publication',
];

public function user()
{
    return $this->belongsTo(User::class);
}

}

