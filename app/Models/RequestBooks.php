<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class RequestBooks extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tj_requete_book';
    protected $fillable = [
        'id_user_app',
        'depart_name',
        'destination_name',
        'latitude_depart',
        'longitude_depart',
        'latitude_arrivee',
        'longitude_arrivee',
        'place',
        'number_poeple',
        'distance',
        'duree',
        'montant',
        'trajet',
        'statut',
        'statut_paiement',
        'id_conducteur',
        'id_payment_method',
        'date_book',
        'nb_day',
        'heure_depart',
        'cu',
        'creer',
        'modifier',
        'statut_round',
        'heure_retour'

    ];

 
}
