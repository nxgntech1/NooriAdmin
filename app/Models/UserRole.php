<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class UserRole extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user_role';
    protected $fillable = [
        'id',
        'user_id',
        'role_id',
        'created_at'
        
    ];
}
