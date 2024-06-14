<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class bookingtypes extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'bookingtypes';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'bookingtype',
        'status',
        'imageid',
        'fixlatlongs',
        'latitude',
        'longitude',
        'tagline'
    ];
}
