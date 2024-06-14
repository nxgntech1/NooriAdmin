<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class RentalVehicleType extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tj_type_vehicule_rental';
    protected $fillable = [
        'libelle',
        'prix',
        'image',
        'creer',
        'modifier',
    ];
    protected $casts = [
        'id' => 'string',
      ];
 
}
