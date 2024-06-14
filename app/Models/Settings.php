<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Settings extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tj_settings';
    public $timestamps = false;
    protected $fillable = [
        'title',
        'footer',
        'email',
        'creer',
        'modifier',
        'delivery_distance',
        'minimum_deposit_amount',
        'minimum_withdrawal_amount',
        'referral_amount',
        'parcel_active',
        'parcel_per_weight_charge',
        'delivery_charge_parcel'

    ];


}
