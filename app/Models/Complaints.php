<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Complaints extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tj_complaints';
    
    public $timestamps = false;
    
    protected $fillable = [
        'title',
        'description',
        'user_type',
        'id_user_app',
        'id_conducteur',
        'status',
        'id_ride',
        'id_parcel'
    ];
}
