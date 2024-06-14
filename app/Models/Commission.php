<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Commission extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tj_commission';
    protected $fillable = [
        'libelle',
        'value',
        'type',
        'statut',
        'creer',
        'modifier',

    ];

 
}
