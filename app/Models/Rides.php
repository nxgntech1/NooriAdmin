<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rides extends Authenticatable
{
    protected $table = 'tj_requete';
    public $timestamps = false;
    protected $fillable = [
        'id_user_app', 
        'depart_name',
        'destination_name', 
        'latitude_depart',
        'longitude_depart', 
        'latitude_arrivee', 
        'longitude_arrivee', 
        'stops', 
        'place', 
        'number_poeple', 
        'distance', 
        'distance_unit',
        'duree', 
        'montant',
        'tip_amount', 
        'tax',
        'discount',
        'admin_commission', 
        'transaction_id', 
        'trajet',
        'statut', 
        'statut_paiement', 
        'id_conducteur', 
        'id_payment_method', 
        'creer',
        'modifier', 
        'date_retour',
        'heure_retour'
        
    ];


}
