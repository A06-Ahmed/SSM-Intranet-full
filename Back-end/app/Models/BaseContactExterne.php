<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseContactExterne extends Model
{
    protected $table = 'base_contacts_externes';

    public $timestamps = false;

    protected $fillable = [
        'organisation',
        'inerlocuteur',
        'telephone_portable',
        'code',
        'ligne_fixe',
        'courriel',
    ];
}
