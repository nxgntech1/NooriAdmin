<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Authenticatable
{
    protected $table = 'tj_country';
    protected $fillable = [
        'libelle',
        'code',
        'statut',
        'creer',
        'modifier'
    ];

 
}
