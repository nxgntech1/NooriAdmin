<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Note extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tj_note';
    protected $fillable = [
        'niveau',
        'id_conducteur',
        'id_user_app',
        'id_conducteur',
        'statut',
        'comment',
        'creer',
        'modifier',
        'ride_id',
        'parcel_id'

    ];

 
}
