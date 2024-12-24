<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requests extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
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
        'place',
        'number_poeple',
        'distance',
        'duree',
        'montant',
        'tip_amount',
        'trajet',
        'statut',
        'statut_paiement',
        'id_conducteur',
        'id_payment_method',
        'creer',
        'modifier',
        'date_retour',
        'heure_retour',
        'statut_round',
        'statut_course',
        'id_conducteur_accepter',
        'trip_objective',
        'trip_category',
        'age_children1',
        'age_children2',
        'age_children3',
        'feel_safe',
        'feel_safe_driver',
        'car_driver_confirmed',
        'admin_commission',
        'bookfor_others_mobileno',
        'bookfor_others_name',
        'odometer_start_reading',
        'odometer_arrival_reading',
        'cancelby',
        'cancel_remarks',
        'cancel_date',
        'booking_type_id',
        'brand_id',
        'model_id',
        'vehicle_Id',
        'ride_for_others_phone_no',
        'coupon_id', 
        'tax_amount',
        'distance_to_pickup', 
        'odometer_end_reading',
        'cod_collected_transaction_id',
        'car_price',
        'sub_total',
        'addon', 
        'is_rescheduled',
        'addon_hrs',
        'duty_slip_no'
    ];
    protected $casts = [
        'id' => 'string',
    ];
}
