<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Authenticatable
{
    protected $table = 'tj_currency';
    protected $fillable = [
        'libelle',
        'symbole',
        'statut',
        'symbol_at_right',
        'creer',
        'modifier'
    ];

 
}
