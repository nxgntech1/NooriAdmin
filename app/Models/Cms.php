<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Cms extends Model
{
// use HasApiTokens, HasFactory, Notifiable, SoftDeletes;    
use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false;

    protected $table = 'tj_cms';
    protected $primaryKey = 'cms_id';
    protected $fillable = [
        'cms_name',
        'cms_slug',
        'cms_desc',
        'cms_status',
    ];

}
