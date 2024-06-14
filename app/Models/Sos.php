<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Sos extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tj_sos';
    
    public $timestamps = false;
    
    protected $fillable = [    
        'ride_id',
        'latitude',
        'longitude',
    ];
}
