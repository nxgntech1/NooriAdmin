<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Referral extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'referral';
    protected $fillable = [
        'id',
        'referral_by_id',
        'referral_code',
        'creer',
        
    ];

    public $timestamps = false;


}
