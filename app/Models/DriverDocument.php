<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class DriverDocument extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admin_documents';
    protected $fillable = [
        'id',
        'title',
        'is_enabled',
        'created_at',
        'updated_at',

    ];

 
}
