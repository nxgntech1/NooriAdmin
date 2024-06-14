<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ParcelCategory extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'parcel_category';
    protected $fillable = [
        'title',
        'image',
        'status',
    ];
    protected $casts = [
        'id' => 'string',
    ];


}
