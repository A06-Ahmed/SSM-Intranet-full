<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseContactInterne extends Model
{
    protected $table = 'base_contacts_internes';

    public $timestamps = false;

    protected $fillable = [
        'nom_et_prenom',
        'affectation',
        'site',
        'extension',
        'telephone_portable',
        'code',
        'fixe_directe',
        'courriel',
        'matricule',
    ];
}
