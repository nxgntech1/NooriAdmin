<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentSettings extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    public $timestamps = false;

    protected $table = 'payment_settings';
    protected $fillable = [
        
        'creer',
        'modifier',

    ];

 
}
