<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Withdrawal extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'withdrawals';
    protected $fillable = [
        'id',
        'amount',
        'statut',
        'id_conducteur',
        'creer',
        'modifier',

    ];

 
}
