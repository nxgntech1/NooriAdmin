<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false;
    protected $table = 'tj_vehicule';
    protected $fillable = [
        'brand',
        'model',
        'color',
        'numberplate',
        'car_make',
        'milage',
        'km',
        'passenger',
        'id_conducteur',
        'id_type_vehicule',
        'statut',
        'creer',
        'modifier',
        'primary_image_id'

    ];
    protected $casts = [
        'id' => 'string',
    ];

 
}
