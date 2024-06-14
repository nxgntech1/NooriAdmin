<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleLocation extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tj_location_vehicule';
   
    public $timestamps = false;
    protected $fillable = [
        'id',
        'nb_jour',
        'date_debut',
        'date_fin',
        'contact',
        'longitude_arrivee',
        'statut',
        'id_vehicule_rental',
        'id_user_app',
        'creer',
        'modifier',

    ];
    // protected $hidden = ['deleted_at'];
 
}
