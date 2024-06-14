<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ParcelOrder extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'parcel_orders';
    protected $fillable = [
        'id_user_app',
        'source',
        'destination',
        'lat_source',
        'lng_source',
        'lat_destination',
        'lng_destination',
        'source_city',
        'destination_city',
        'sender_name',
        'sender_phone',
        'receiver_name',
        'receiver_phone',
        'parcel_weight',
        'parcel_image',
        'parcel_type',
        'parcel_dimension',
        'note',
        'parcel_date',
        'parcel_time',
        'receive_date',
        'receive_time',
        'status',
        'payment_status',
        'id_payment_method',
        'tax',
        'discount',
        'admin_commission',
        'amount', 
        'id_driver',
        'rejected_driver_ids',
        'otp',
        'distance',
        'distance_unit',
        'reason',
        'duration',
        'tip'
    ];
    protected $casts = [
        'id'=>'string',
    ];
}
