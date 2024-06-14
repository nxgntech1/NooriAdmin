<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;


class VehicleRental extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
  
    protected $table = 'tj_vehicule_rental';
    protected $primaryKey = 'id';
    public $timestamps = false;
  
    protected $fillable = [
        'nombre',
        'statut',
        'prix',
        'nb_place',
        'image',
        'id_type_vehicule_rental',
        'creer',
        'modifier',

    ];

 
}
