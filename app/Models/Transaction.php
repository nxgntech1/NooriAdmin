<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Transaction extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tj_transaction';
    protected $fillable = [
        'amount',
        'id_user_app',
        'creer',
        'modifier',

    ];

 
}
