<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserApp extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'tj_user_app';
    public $timestamps = false;
    protected $fillable = [
        'nom',
        'email',
        'prenom',
        'email',
        'phone',
        'mdp',
        'login_type',
        'photo',
        'photo_path',
        'photo_nic',
        'photo_nic_path',
        'statut',
        'statut_nic',
        'tonotify',
        'device_id',
        'fcm_id',
        'creer',
        'modifier',
        'amount',
        'reset_password_otp',
        'reset_password_otp_modifier',
        'age',
        'gender'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
     protected $casts = [
     'id' => 'string',
   ];

}
