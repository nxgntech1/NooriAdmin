<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleType extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    public $timestamps = false;
    protected $table = 'tj_type_vehicule';
    protected $fillable = [
        'libelle',
        'prix',
        'image',
        'selected_image',
        'creer',
        'modifier'
    ];
    protected $casts = [
        'id' => 'string',
      ];

 
}
