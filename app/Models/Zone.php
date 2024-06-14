<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use HasSpatial;

    protected $fillable = [
        'name',
        'status',
        'latitude',
        'longitude',
        'area',
    ];

    protected $casts = [
        'area' => Polygon::class,
    ];
}
