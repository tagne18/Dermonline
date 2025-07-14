<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $fillable = [
        'user_id',
        'reference',
        'montant',
        'transaction_id',
        'date',
        'details',
    ];
}
