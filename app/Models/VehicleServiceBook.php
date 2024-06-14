<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class VehicleServiceBook extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tj_vehicule_service_book';
    protected $fillable = [
        'id_conducteur',
        'km',
        'photo_car_service_book',
        'photo_car_service_book_path',
        'file_name',
        'creer',
        'modifier',

    ];
    protected $casts = [
        'id' => 'string',
    ];
 
}
