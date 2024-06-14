<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class pricing_by_car_models extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'pricing_by_car_models';
    public $timestamps = false;
    protected $fillable = [
        'PricingID'  ,
        'CarModelID', 
        'BookingTypeID', 
        'Price',
        'Status'
    ];
}
