<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\API\v1\GcmController;
use App\Http\Controllers\Controller;
use App\Models\Requests;
use App\Models\Settings;
use App\Models\UserApp;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Models\Currency;
use Illuminate\Support\Str;

class RideDetailsController extends Controller
{

    public function __construct()
    {
        $this->limit = 20;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = UserApp::all();
        $users = UserApp::paginate($this->limit);
        return response()->json($users);
    }

    public function ridedetails(Request $request)
    {

        $ride_id = $request->get('ride_id');

        if (!empty($ride_id)) {

            $row = DB::table('tj_requete')
                ->leftJoin('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
                ->select('tj_requete.*','tj_user_app.id as existing_user_id')
                ->where('tj_requete.id', $ride_id)->first();

            if ($row) {
                $row->id = (string) $row->id;
                $row->stops = json_decode($row->stops, true);
                $row->tax = json_decode($row->tax,true);
                $row->user_info = json_decode($row->user_info, true);
                $row->discount = number_format((float) $row->discount, 2, '.', '');
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'successfully';
                $response['data'] = $row;
            } else {
                $response['success'] = 'Failed';
                $response['error'] = 'Ride Not Found';
            }

        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Some fields not found';
        }

        return response()->json($response);
    }

    public function getRideReview(Request $request)
    {

        $ride_id = $request->get('ride_id');
        $review_of = $request->get('review_of');
        $user_id = $request->get('user_id');
        $ride_type = $request->get('ride_type');
        if (empty($ride_id)) {
            $response['success'] = 'Failed';
            $response['error'] = 'Ride Id Missing';
        } else if (empty($review_of)) {
            $response['success'] = 'Failed';
            $response['error'] = 'Review of Missing';
        } else if ($review_of == "customer" && empty($user_id)) {
            $response['success'] = 'Failed';
            $response['error'] = 'Driver Id missing';
        } else if ($review_of == "driver" && empty($user_id)) {
            $response['success'] = 'Failed';
            $response['error'] = 'User Id missing';
        } else {

            if ($review_of == "customer") {
                $review = DB::table('tj_user_note')->where('ride_id', $ride_id)->where('id_conducteur', $user_id)->first();
            } else if ($review_of == "driver") {
                if($ride_type=='parcel'){
                    $review = DB::table('tj_note')->where('parcel_id', $ride_id)->where('id_user_app', $user_id)->first();
                } else {
                    $review = DB::table('tj_note')->where('ride_id', $ride_id)->where('id_user_app', $user_id)->first();
                }
            }

            if ($review) {
                $review->id = (string) $review->id;
                $response['success'] = 'Success';
                $response['error'] = null;
                $response['data'] = $review;
            } else {
                $response['success'] = 'Failed';
                $response['error'] = 'No review found';
            }
        }

        return response()->json($response);
    }

    public function getUserRides(Request $request)
    {

        $id_user_app = $request->get('id_user_app');
        if (empty($id_user_app)) {
            $response['success'] = 'Failed';
            $response['error'] = 'Missing id_user_app';
            return response()->json($response);
        }

        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $sql = DB::table('tj_requete')
            ->Join('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
            //->Join('tj_conducteur', 'tj_conducteur.id', '=', 'tj_requete.id_conducteur')
            ->Join('car_model', 'car_model.id', '=', 'tj_requete.model_id')
            ->Join('brands', 'brands.id', '=', 'tj_requete.brand_id')
            ->Join('tj_payment_method', 'tj_payment_method.id', '=', 'tj_requete.id_payment_method')
            //->Join('tj_payment_method', 'tj_payment_method.id', '=', 'tj_requete.id_payment_method')
            ->select('tj_requete.id', 'tj_requete.ride_type', 'tj_requete.id_user_app', 'tj_requete.depart_name',
                'tj_requete.distance_unit', 'tj_requete.destination_name', 'tj_requete.latitude_depart',
                'tj_requete.longitude_depart', 'tj_requete.latitude_arrivee', 'tj_requete.longitude_arrivee',
                'tj_requete.number_poeple', 'tj_requete.place', 'tj_requete.statut', 'tj_requete.id_conducteur',
                'tj_requete.creer', 'tj_requete.trajet', 'tj_requete.trip_objective', 'tj_requete.trip_category',
                'tj_requete.tax','tj_requete.discount','tj_requete.tip_amount','tj_requete.montant',
                'tj_requete.admin_commission', 'tj_user_app.nom', 'tj_user_app.prenom', 'tj_requete.otp',
                'tj_requete.distance', 'tj_user_app.phone', 'tj_user_app.photo_path as userphoto',
                'tj_requete.date_retour', 'tj_requete.heure_retour', 'tj_requete.statut_round',
                'tj_requete.montant', 'tj_requete.duree', 'tj_requete.statut_paiement',
                'tj_requete.vehicle_Id','tj_requete.id_conducteur','car_model.name as carmodel','brands.name as brandname',
                'tj_payment_method.libelle as payment', 'tj_payment_method.image as payment_image', 'tj_requete.stops')
            //->where('tj_requete.id_payment_method', '=', DB::raw('tj_payment_method.id'))
            ->where('tj_requete.id_user_app', '=', $id_user_app)
            //->where('tj_requete.id_conducteur', '=', DB::raw('tj_conducteur.id'))
            ->orderBy('tj_requete.id', 'desc')
            ->get();

        foreach ($sql as $row) {
            $row->id = (string) $row->id;
            $row->stops = json_decode($row->stops, true);
            $row->tax = json_decode($row->tax, true);
            $row->brand = $row->brandname;
            $row->model = $row->carmodel;

            $id_conducteur = $row->id_conducteur;
            $id_vehicle = $row->vehicle_Id;

            if ($row->ride_type == "dispatcher") {
                $row->ride_type = "dispatcher";
            }
            elseif ($row->ride_type == "driver") {
                $row->ride_type = "driver";
            }else{
                $row->ride_type = "normal";
            }

            if ($row->otp == null) {
                $row->otp = "";
            }


            // $driver_id = $row->id_conducteur;

            //     $row->idVehicule = (string) $row_vehicle->id;
                
            //     $row->car_make = $row_vehicle->car_make;
            //     $row->milage = $row_vehicle->milage;
            //     $row->km = $row_vehicle->km;
            //     $row->color = $row_vehicle->color;
            //     $row->numberplate = $row_vehicle->numberplate;
            //     $row->passenger = $row_vehicle->passenger;
            //     $row->vehicle_imageid = $row_vehicle->primary_image_id;

            if (!empty($id_vehicle))
            {
                $sql_vehicle = DB::table('tj_vehicule')
                ->select('tj_vehicule.id', 'tj_vehicule.car_make', 'tj_vehicule.milage', 'tj_vehicule.km',
                 'tj_vehicule.color', 'tj_vehicule.numberplate',
                'tj_vehicule.passenger', 'tj_vehicule.primary_image_id' )
                ->where('id', '=', $id_vehicle)->get();

                foreach ($sql_vehicle as $row_vehicle) {
                    $row->idVehicule = (string) $row_vehicle->id;
                
                    $row->car_make = $row_vehicle->car_make;
                    $row->milage = $row_vehicle->milage;
                    $row->km = $row_vehicle->km;
                    $row->color = $row_vehicle->color;
                    $row->numberplate = $row_vehicle->numberplate;
                    $row->passenger = $row_vehicle->passenger;
                    $row->vehicle_imageid = $row_vehicle->primary_image_id;
                }
            }

            if ($row->trajet != '') {
                if (file_exists(public_path('images/recu_trajet_course' . '/' . $row->trajet))) {
                    $image_trajet = asset('images/recu_trajet_course') . '/' . $row->trajet;
                } else {
                    $image_trajet = asset('assets/images/placeholder_image.jpg');
                }
                $row->trajet = $image_trajet;
            }

            

            if ($row->vehicle_imageid != '') {
                if (file_exists(public_path('assets/images/vehicule' . '/' . $row->vehicle_imageid))) {
                    $vehicle_imageid = asset('assets/images/vehicule') . '/' . $row->vehicle_imageid;
                } else {
                    $vehicle_imageid = asset('assets/images/placeholder_image.jpg');
                }
                $row->vehicle_imageid = $vehicle_imageid;
            }

            if ($row->payment_image != '') {
                if (file_exists(public_path('assets/images/payment_method' . '/' . $row->payment_image))) {
                    $image = asset('assets/images/payment_method') . '/' . $row->payment_image;
                } else {
                    $image = asset('assets/images/placeholder_image.jpg');
                }
                $row->payment_image = $image;
            }

            if ($id_conducteur != 0) {

                $sql_cond = DB::table('tj_conducteur')->select('nom as nomConducteur', 
                'prenom as prenomConducteur', 'phone as driverPhone', 'photo_path as driverphoto')
                ->where('id', '=', $id_conducteur)
                ->get();
                
                // 'tj_conducteur.nom as nomConducteur', 'tj_conducteur.prenom as prenomConducteur', 'tj_conducteur.phone as driverPhone', 'tj_conducteur.photo_path as driverphoto',
                foreach ($sql_cond as $row_cond)
                {
                    $row->nomConducteur = $row_cond->nomConducteur;
                    $row->driverPhone = $row_cond->driverPhone;
                    $row->driverphoto = $row_cond->driverphoto;

                }

                if ($row->driverphoto != '') {
                    if (file_exists(public_path('assets/images/driver' . '/' . $row->driverphoto))) {
                        $image_driver = asset('assets/images/driver') . '/' . $row->driverphoto;
                    } else {
                        $image_driver = asset('assets/images/placeholder_image.jpg');
                    }
                    $row->driverphoto = $image_driver;
                }

                    
            } else {
                $row->nomConducteur = "";
                $row->prenomConducteur = "";
                $row->moyenne = "0.0";
                $row->driver_phone = "";
                $row->moyenne_driver = "0.0";
            }
            $row->creer = date("d", strtotime($row->creer)) . " " . $months[date("F", strtotime($row->creer))] . ". " . date("Y", strtotime($row->creer));
            $row->date_retour = date("d", strtotime($row->date_retour)) . " " . $months[date("F", strtotime($row->date_retour))] . ", " . date("Y", strtotime($row->date_retour));

            $output[] = $row;

        }
        if (!empty($output)) {
            $response['success'] = 'success';
            $response['error'] = null;
            $response['message'] = 'Successfully';
            $response['data'] = $output;
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Failed to fetch data';
        }

        return response()->json($response);
    }

    public function getUserAllRides(Request $request)
    {


        $id_user_app = $request->get('id_user_app');
        $latest = $request->get('latest');
        if (empty($id_user_app)) {
            $response['success'] = 'Failed';
            $response['error'] = 'Missing id_user_app';
            return response()->json($response);
        }

        $currency = Currency::where('statut', 'yes')->first();

        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        //  if (!empty($latest) && $latest = 'yes'){
        //     $sql = DB::table('tj_requete')
        //     ->Join('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
        //     ->Join('car_model', 'car_model.id', '=', 'tj_requete.model_id')
        //     ->Join('brands', 'brands.id', '=', 'tj_requete.brand_id')
        //     ->Join('tj_payment_method', 'tj_payment_method.id', '=', 'tj_requete.id_payment_method')
        //     ->select('tj_requete.id', 'tj_requete.ride_type', 'tj_requete.id_user_app', 'tj_requete.depart_name',
        //         'tj_requete.distance_unit', 'tj_requete.destination_name', 'tj_requete.latitude_depart',
        //         'tj_requete.longitude_depart', 'tj_requete.latitude_arrivee', 'tj_requete.longitude_arrivee',
        //         'tj_requete.number_poeple', 'tj_requete.place', 'tj_requete.statut', 'tj_requete.id_conducteur',
        //         'tj_requete.creer', 'tj_requete.trajet', 'tj_requete.trip_objective', 'tj_requete.trip_category',
        //         'tj_requete.tax','tj_requete.discount','tj_requete.tip_amount','tj_requete.montant',
        //         'tj_requete.admin_commission', 'tj_user_app.nom', 'tj_user_app.prenom', 'tj_requete.otp',
        //         'tj_requete.distance', 'tj_user_app.phone', 'tj_user_app.photo_path as userphoto',
        //         'tj_requete.date_retour', 'tj_requete.heure_retour', 'tj_requete.statut_round',
        //         'tj_requete.montant', 'tj_requete.duree', 'tj_requete.statut_paiement',
        //         'tj_requete.vehicle_Id','tj_requete.id_conducteur','car_model.name as carmodel','brands.name as brandname',
        //         'tj_payment_method.libelle as payment', 'tj_payment_method.image as payment_image', 'tj_requete.stops')
        //     ->where('tj_requete.id_user_app', '=', $id_user_app)
        //     ->where('tj_requete.id', '=',  '173')
        //     ->orderBy('tj_requete.id', 'desc')
        //     ->get();
                
                
        // }else{
            $sql = DB::table('tj_requete')
            ->Join('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
            ->Join('car_model', 'car_model.id', '=', 'tj_requete.model_id')
            ->Join('brands', 'brands.id', '=', 'tj_requete.brand_id')
            ->Join('tj_payment_method', 'tj_payment_method.id', '=', 'tj_requete.id_payment_method')
            ->select('tj_requete.id', 'tj_requete.ride_type', 'tj_requete.id_user_app', 'tj_requete.depart_name',
                'tj_requete.distance_unit', 'tj_requete.destination_name', 'tj_requete.latitude_depart',
                'tj_requete.longitude_depart', 'tj_requete.latitude_arrivee', 'tj_requete.longitude_arrivee',
                'tj_requete.number_poeple', 'tj_requete.place', 'tj_requete.statut', 'tj_requete.id_conducteur',
                'tj_requete.creer', 'tj_requete.trajet', 'tj_requete.trip_objective', 'tj_requete.trip_category',
                'tj_requete.tax','tj_requete.discount','tj_requete.tip_amount','tj_requete.montant',
                'tj_requete.admin_commission', 'tj_user_app.nom', 'tj_user_app.prenom', 'tj_requete.otp',
                'tj_requete.distance', 'tj_user_app.phone', 'tj_user_app.photo_path as userphoto',
                'tj_requete.date_retour', 'tj_requete.heure_retour', 'tj_requete.statut_round',
                'tj_requete.montant', 'tj_requete.duree', 'tj_requete.statut_paiement',
                'tj_requete.ride_required_on_date','tj_requete.ride_required_on_time',
                'tj_requete.car_Price','tj_requete.sub_total',
                'tj_requete.vehicle_Id','tj_requete.id_conducteur','car_model.name as carmodel','brands.name as brandname',
                'tj_payment_method.libelle as payment', 'tj_payment_method.image as payment_image')
            ->where('tj_requete.id_user_app', '=', $id_user_app)
            ->orderBy('tj_requete.id', 'desc')
            ->get();
        //}

        
        if (!empty($sql)){
            foreach ($sql as $row) {
                $row->id = (string) $row->id;
                $row->brand = $row->brandname;
                $row->model = $row->carmodel;

                $id_conducteur = $row->id_conducteur;
                $id_vehicle = $row->vehicle_Id;

                $row->depart_name = $row->depart_name;
                $row->destination_name = $row->destination_name;
                $row->statut = Str::lower($row->statut);
                $row->montant = $row->montant;// $currency->symbole . "" . number_format($row->montant,$currency->decimal_digit);
                $row->BookigDate = $row->ride_required_on_date;
                $row->BookingTime = $row->ride_required_on_time;
                $row->distance = $row->distance;
                $row->distance_unit = $row->distance_unit;
                $row->latitude_depart = $row->latitude_depart;
                $row->longitude_depart = $row->longitude_depart;
                $row->latitude_arrivee = $row->latitude_arrivee;
                $row->longitude_arrivee = $row->longitude_arrivee;
                $row->duree = $row->duree;


                if ($row->otp == null) {
                    $row->otp = "";
                }else{
                    $row->otp = $row->otp;
                }

                if (!empty($id_vehicle))
                {
                    $sql_vehicle = DB::table('tj_vehicule')
                    ->select('tj_vehicule.id', 'tj_vehicule.car_make', 'tj_vehicule.milage', 'tj_vehicule.km',
                    'tj_vehicule.color', 'tj_vehicule.numberplate',
                    'tj_vehicule.passenger', 'tj_vehicule.primary_image_id' )
                    ->where('id', '=', $id_vehicle)->get();

                    foreach ($sql_vehicle as $row_vehicle) {
                        $row->idVehicule = (string) $row_vehicle->id;
                    
                        $row->car_make = $row_vehicle->car_make;
                        $row->milage = $row_vehicle->milage;
                        $row->km = $row_vehicle->km;
                        $row->color = $row_vehicle->color;
                        $row->numberplate = $row_vehicle->numberplate;
                        $row->passenger = $row_vehicle->passenger;
                        $row->vehicle_imageid = $row_vehicle->primary_image_id;
                    }
                }

                if (!empty($row->vehicle_imageid)) {
                    if (file_exists(public_path('assets/images/vehicule' . '/' . $row->vehicle_imageid))) {
                        $vehicle_imageid = asset('assets/images/vehicule') . '/' . $row->vehicle_imageid;
                    } else {
                        $vehicle_imageid = asset('assets/images/placeholder_image.jpg');
                    }
                    $row->vehicle_imageid = $vehicle_imageid;
                }

                if ($row->payment_image != '') {
                    if (file_exists(public_path('assets/images/payment_method' . '/' . $row->payment_image))) {
                        $image = asset('assets/images/payment_method') . '/' . $row->payment_image;
                    } else {
                        $image = asset('assets/images/placeholder_image.jpg');
                    }
                    $row->payment_image = $image;
                }

                if (!empty($id_conducteur)) {

                    $sql_cond = DB::table('tj_conducteur')->select('nom as nomConducteur', 
                    'prenom as prenomConducteur', 'phone as driverPhone', 'photo_path as driverphoto')
                    ->where('id', '=', $id_conducteur)
                    ->get();
                    
                    // 'tj_conducteur.nom as nomConducteur', 'tj_conducteur.prenom as prenomConducteur', 'tj_conducteur.phone as driverPhone', 'tj_conducteur.photo_path as driverphoto',
                    foreach ($sql_cond as $row_cond)
                    {
                        $row->nomConducteur = $row_cond->nomConducteur;
                        $row->driverPhone = $row_cond->driverPhone;
                        $row->driverphoto = $row_cond->driverphoto;
    
                    }
    
                    if ($row->driverphoto != '') {
                        if (file_exists(public_path('assets/images/driver' . '/' . $row->driverphoto))) {
                            $image_driver = asset('assets/images/driver') . '/' . $row->driverphoto;
                        } else {
                            $image_driver = asset('assets/images/placeholder_image.jpg');
                        }
                        $row->driverphoto = $image_driver;
                    }
    
                        
                } else {
                    $row->nomConducteur = "";
                    //$row->prenomConducteur = "";
                   // $row->moyenne = "0.0";
                    $row->driver_phone = "";
                    //$row->moyenne_driver = "0.0";
                }

                $output[] = $row;

            }
        }
        if (!empty($output)) {
            $response['success'] = 'success';
            $response['error'] = null;
            $response['message'] = 'Successfully';
            $response['data'] = $output;
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Failed to fetch data';
        }

        return response()->json($response);
    }

    public function getUserLatestRides(Request $request)
    {


        $id_user_app = $request->get('id_user_app');
        

        if (empty($id_user_app)) {
            $response['success'] = 'Failed';
            $response['error'] = 'Missing id_user_app';
            return response()->json($response);
        }

        $currency = Currency::where('statut', 'yes')->first();

        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $sqllatestrideid = DB::table('tj_requete')
                        ->select('tj_requete.id')
                        ->where('tj_requete.id_user_app', '=', $id_user_app)
                        ->orderBy('tj_requete.id', 'desc')
                        ->first();
        if($sqllatestrideid)
        {
                  $rideid = $sqllatestrideid->id;

        $sql = DB::table('tj_requete')
        ->Join('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
        ->Join('car_model', 'car_model.id', '=', 'tj_requete.model_id')
        ->Join('brands', 'brands.id', '=', 'tj_requete.brand_id')
        ->Join('tj_payment_method', 'tj_payment_method.id', '=', 'tj_requete.id_payment_method')
        ->select('tj_requete.id', 'tj_requete.ride_type', 'tj_requete.id_user_app', 'tj_requete.depart_name',
            'tj_requete.distance_unit', 'tj_requete.destination_name', 'tj_requete.latitude_depart',
            'tj_requete.longitude_depart', 'tj_requete.latitude_arrivee', 'tj_requete.longitude_arrivee',
            'tj_requete.number_poeple', 'tj_requete.place', 'tj_requete.statut', 'tj_requete.id_conducteur',
            'tj_requete.creer', 'tj_requete.trajet', 'tj_requete.trip_objective', 'tj_requete.trip_category',
            'tj_requete.tax','tj_requete.discount','tj_requete.tip_amount','tj_requete.montant',
            'tj_requete.admin_commission', 'tj_user_app.nom', 'tj_user_app.prenom', 'tj_requete.otp',
            'tj_requete.distance', 'tj_user_app.phone', 'tj_user_app.photo_path as userphoto',
            'tj_requete.date_retour', 'tj_requete.heure_retour', 'tj_requete.statut_round',
            'tj_requete.montant', 'tj_requete.duree', 'tj_requete.statut_paiement',
            'tj_requete.ride_required_on_date','tj_requete.ride_required_on_time',
            'tj_requete.car_Price','tj_requete.sub_total',
            'tj_requete.vehicle_Id','tj_requete.id_conducteur','car_model.name as carmodel','brands.name as brandname',
            'tj_payment_method.libelle as payment', 'tj_payment_method.image as payment_image')
            ->where('tj_requete.id_user_app', '=', $id_user_app)
            ->where('tj_requete.id', '=', $rideid)
            //->orderBy('tj_requete.id', 'desc')
            ->get();
              
            
       
        if (!empty($sql)){

            //$sql1 = $sql->first();

            foreach ($sql as $row) {
                $row->id = (string) $row->id;
                $row->brand = $row->brandname;
                $row->model = $row->carmodel;

                $id_conducteur = $row->id_conducteur;
                $id_vehicle = $row->vehicle_Id;

                $row->depart_name = $row->depart_name;
                $row->destination_name = $row->destination_name;
                $row->statut = Str::lower($row->statut);
                $row->montant = $currency->symbole . "" . number_format($row->montant,$currency->decimal_digit);
                $row->BookigDate = $row->ride_required_on_date;
                $row->BookingTime = $row->ride_required_on_time;
                $row->distance = $row->distance;
                $row->distance_unit = $row->distance_unit;
                $row->latitude_depart = $row->latitude_depart;
                $row->longitude_depart = $row->longitude_depart;
                $row->latitude_arrivee = $row->latitude_arrivee;
                $row->longitude_arrivee = $row->longitude_arrivee;
                $row->duree = $row->duree;


                if ($row->otp == null) {
                    $row->otp = "";
                }else{
                    $row->otp = $row->otp;
                }

                if (!empty($id_vehicle))
                {
                    $sql_vehicle = DB::table('tj_vehicule')
                    ->select('tj_vehicule.id', 'tj_vehicule.car_make', 'tj_vehicule.milage', 'tj_vehicule.km',
                    'tj_vehicule.color', 'tj_vehicule.numberplate',
                    'tj_vehicule.passenger', 'tj_vehicule.primary_image_id' )
                    ->where('id', '=', $id_vehicle)->get();

                    foreach ($sql_vehicle as $row_vehicle) {
                        $row->idVehicule = (string) $row_vehicle->id;
                    
                        $row->car_make = $row_vehicle->car_make;
                        $row->milage = $row_vehicle->milage;
                        $row->km = $row_vehicle->km;
                        $row->color = $row_vehicle->color;
                        $row->numberplate = $row_vehicle->numberplate;
                        $row->passenger = $row_vehicle->passenger;
                        $row->vehicle_imageid = $row_vehicle->primary_image_id;
                    }
                }

                if (!empty($row->vehicle_imageid)) {
                    if (file_exists(public_path('assets/images/vehicule' . '/' . $row->vehicle_imageid))) {
                        $vehicle_imageid = asset('assets/images/vehicule') . '/' . $row->vehicle_imageid;
                    } else {
                        $vehicle_imageid = asset('assets/images/placeholder_image.jpg');
                    }
                    $row->vehicle_imageid = $vehicle_imageid;
                }

                if ($row->payment_image != '') {
                    if (file_exists(public_path('assets/images/payment_method' . '/' . $row->payment_image))) {
                        $image = asset('assets/images/payment_method') . '/' . $row->payment_image;
                    } else {
                        $image = asset('assets/images/placeholder_image.jpg');
                    }
                    $row->payment_image = $image;
                }

                if (!empty($id_conducteur)) {

                    $sql_cond = DB::table('tj_conducteur')->select('nom as nomConducteur', 
                    'prenom as prenomConducteur', 'phone as driverPhone', 'photo_path as driverphoto')
                    ->where('id', '=', $id_conducteur)
                    ->get();
                    
                    // 'tj_conducteur.nom as nomConducteur', 'tj_conducteur.prenom as prenomConducteur', 'tj_conducteur.phone as driverPhone', 'tj_conducteur.photo_path as driverphoto',
                    foreach ($sql_cond as $row_cond)
                    {
                        $row->nomConducteur = $row_cond->nomConducteur;
                        $row->driverPhone = $row_cond->driverPhone;
                        $row->driverphoto = $row_cond->driverphoto;
    
                    }
    
                    if ($row->driverphoto != '') {
                        if (file_exists(public_path('assets/images/driver' . '/' . $row->driverphoto))) {
                            $image_driver = asset('assets/images/driver') . '/' . $row->driverphoto;
                        } else {
                            $image_driver = asset('assets/images/placeholder_image.jpg');
                        }
                        $row->driverphoto = $image_driver;
                    }
    
                        
                } else {
                    $row->nomConducteur = "";
                    //$row->prenomConducteur = "";
                   // $row->moyenne = "0.0";
                    $row->driver_phone = "";
                    //$row->moyenne_driver = "0.0";
                }

                $output[] = $row;

            }
        }
        

        if (!empty($output)) {
            $response['success'] = 'success';
            $response['error'] = null;
            $response['message'] = 'Successfully';
            $response['data'] = $output;
        } else {
            $response['success'] = $rideid;
            $response['error'] = 'Failed to fetch data';
        }
    }
    else{
        $response['success'] = 'Failed';
            $response['error'] = 'Failed to fetch data';
    }

        return response()->json($response);
    }

    public function getUserRidesDetails(Request $request)
    {

        $id_user_app = $request->get('id_user_app');
        $ride_id = $request->get('ride_id');

        if (empty($id_user_app)) {
            $response['success'] = 'Failed';
            $response['error'] = 'Missing id_user_app';
            return response()->json($response);
        }

        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $sql = DB::table('tj_requete')
        ->Join('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
        ->Join('car_model', 'car_model.id', '=', 'tj_requete.model_id')
        ->Join('brands', 'brands.id', '=', 'tj_requete.brand_id')
        ->Join('tj_payment_method', 'tj_payment_method.id', '=', 'tj_requete.id_payment_method')
        ->select('tj_requete.id', 'tj_requete.ride_type', 'tj_requete.id_user_app', 'tj_requete.depart_name',
            'tj_requete.distance_unit', 'tj_requete.destination_name', 'tj_requete.latitude_depart',
            'tj_requete.longitude_depart', 'tj_requete.latitude_arrivee', 'tj_requete.longitude_arrivee',
            'tj_requete.number_poeple', 'tj_requete.place', 'tj_requete.statut', 'tj_requete.id_conducteur',
            'tj_requete.creer', 'tj_requete.trajet', 'tj_requete.trip_objective', 'tj_requete.trip_category',
            'tj_requete.tax','tj_requete.discount','tj_requete.tip_amount','tj_requete.montant',
            'tj_requete.admin_commission', 'tj_user_app.nom', 'tj_user_app.prenom', 'tj_requete.otp',
            'tj_requete.distance', 'tj_user_app.phone', 'tj_user_app.photo_path as userphoto',
            'tj_requete.date_retour', 'tj_requete.heure_retour', 'tj_requete.statut_round',
            'tj_requete.montant', 'tj_requete.duree', 'tj_requete.statut_paiement',
            'tj_requete.car_Price','tj_requete.sub_total',
            'tj_requete.ride_required_on_date','tj_requete.ride_required_on_time','tj_requete.tax_amount','tj_requete.bookfor_others_mobileno','tj_requete.bookfor_others_name',
            'tj_requete.vehicle_Id','tj_requete.id_conducteur','car_model.name as carmodel','brands.name as brandname',
            'tj_payment_method.libelle as payment', 'tj_payment_method.image as payment_image','tj_requete.id_payment_method as paymentmethodid')
            ->where('tj_requete.id_user_app', '=', $id_user_app)
            ->where('tj_requete.id', '=', $ride_id)
            //->orderBy('tj_requete.id', 'desc')
            ->get();
        
        
        if (!empty($sql)){
            foreach ($sql as $row) {
                $row->id = (string) $row->id;
                $row->brand = $row->brandname;
                $row->model = $row->carmodel;

                $id_conducteur = $row->id_conducteur;
                $id_vehicle = $row->vehicle_Id;


                $row->depart_name = $row->depart_name;
                $row->destination_name = $row->destination_name;
                $row->statut = Str::lower($row->statut);
                $row->montant = $row->montant;
                $row->car_Price = $row->car_Price;
                $row->sub_total = $row->sub_total;
                $row->BookigDate = $row->ride_required_on_date;
                $row->BookingTime = $row->ride_required_on_time;
                $row->distance = $row->distance;
                $row->distance_unit = $row->distance_unit;
                $row->latitude_depart = $row->latitude_depart;
                $row->longitude_depart = $row->longitude_depart;
                $row->latitude_arrivee = $row->latitude_arrivee;
                $row->longitude_arrivee = $row->longitude_arrivee;
                $row->duree = $row->duree;

                
                $row->paymentmethodid = $row->paymentmethodid;
                $row->paymentmethod = $row->payment;
                $row->discount = $row->discount;
                $row->tax_amount = $row->tax_amount;
                $row->bookfor_others_mobileno = $row->bookfor_others_mobileno;
                $row->bookfor_others_name = $row->bookfor_others_name;
                


                if ($row->otp == null) {
                    $row->otp = '';
                }else{
                    $row->otp = $row->otp;
                }

                if (!empty($id_vehicle))
                {
                    $sql_vehicle = DB::table('tj_vehicule')
                    ->select('tj_vehicule.id', 'tj_vehicule.car_make', 'tj_vehicule.milage', 'tj_vehicule.km',
                    'tj_vehicule.color', 'tj_vehicule.numberplate',
                    'tj_vehicule.passenger', 'tj_vehicule.primary_image_id')
                    ->where('id', '=', $id_vehicle)->get();

                    foreach ($sql_vehicle as $row_vehicle) {
                        $row->idVehicule = (string) $row_vehicle->id;
                    
                        $row->car_make = $row_vehicle->car_make;
                        $row->milage = $row_vehicle->milage;
                        $row->km = $row_vehicle->km;
                        $row->color = $row_vehicle->color;
                        $row->numberplate = $row_vehicle->numberplate;
                        $row->passenger = $row_vehicle->passenger;
                        $row->vehicle_imageid = $row_vehicle->primary_image_id;
                    }
                }else{
                        $row->idVehicule = "";
                        $row->car_make = "";
                        $row->milage = "";
                        $row->km = "";
                        $row->color = "";
                        $row->numberplate = "";
                        $row->passenger = "";
                        $row->vehicle_imageid = "";
                }

                if (!empty( $row->vehicle_imageid)) {
                    if (file_exists(public_path('assets/images/vehicule' . '/' . $row->vehicle_imageid))) {
                        $vehicle_imageid = asset('assets/images/vehicule') . '/' . $row->vehicle_imageid;
                    } else {
                        $vehicle_imageid = asset('assets/images/placeholder_image.jpg');
                    }
                    $row->vehicle_imageid = $vehicle_imageid;
                }

                if ($row->payment_image != '') {
                    if (file_exists(public_path('assets/images/payment_method' . '/' . $row->payment_image))) {
                        $image = asset('assets/images/payment_method') . '/' . $row->payment_image;
                    } else {
                        $image = asset('assets/images/placeholder_image.jpg');
                    }
                    $row->payment_image = $image;
                }

                $sql_tax = DB::table('ride_tax_details')
                ->select('tax_type', 'tax_label as taxlabel', 'tax', 'ride_tax_amount')
                ->where('bookingid', '=', $row->id)->get();
                
                foreach ($sql_tax as $sql_rowtax) {
                    $sql_rowtax->tax_type = $sql_rowtax->tax_type;
                    $sql_rowtax->taxlabel = $sql_rowtax->taxlabel;
                    $sql_rowtax->tax = $sql_rowtax->tax;
                    $sql_rowtax->ride_tax_amount = $sql_rowtax->ride_tax_amount;

                    //$row->tax = $sql_rowtax;
                    $tax[] = $sql_rowtax;
                }
                $row->tax = $tax;

                if (!empty($id_conducteur)) {

                    $sql_cond = DB::table('tj_conducteur')->select('nom as nomConducteur', 
                    'prenom as prenomConducteur', 'phone as driverPhone', 'photo_path as driverphoto')
                    ->where('id', '=', $id_conducteur)
                    ->get();
                    
                    // 'tj_conducteur.nom as nomConducteur', 'tj_conducteur.prenom as prenomConducteur', 'tj_conducteur.phone as driverPhone', 'tj_conducteur.photo_path as driverphoto',
                    foreach ($sql_cond as $row_cond)
                    {
                        $row->driverPhone = $row_cond->driverPhone;
                        $row->driverphoto = $row_cond->driverphoto;
                        $row->drivername = $row_cond->nomConducteur;

                    }
    
                    
                    if ($row->driverphoto != '') {
                        if (file_exists(public_path('assets/images/driver' . '/' . $row->driverphoto))) {
                            $image_driver = asset('assets/images/driver') . '/' . $row->driverphoto;
                        } else {
                            $image_driver = asset('assets/images/placeholder_image.jpg');
                        }
                        $row->driverphoto = $image_driver;
                    }
    
                        
                } else {
                    $row->nomConducteur = "";
                    //$row->prenomConducteur = "";
                   // $row->moyenne = "0.0";
                    $row->driver_phone = "";
                    //$row->moyenne_driver = "0.0";
                }

                
                $output[] = $row;

            }
        }
        if (!empty($output)) {
            $response['success'] = 'success';
            $response['error'] = null;
            $response['message'] = 'Successfully';
            $response['data'] = $output;
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Failed to fetch data';
        }

        return response()->json($response);
    }

    public function getDriverRides(Request $request)
    {

        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $output = array();

        $settig_data = DB::table('tj_settings')->select('trip_accept_reject_driver_time_sec')->get();
        $trip_accept_reject_driver_time_sec = '';

        $settings = Settings::first();

        if ($settings->trip_accept_reject_driver_time_sec) {
            $trip_accept_reject_driver_time_sec = $settings->trip_accept_reject_driver_time_sec;
        }

        $id_driver = $request->get('id_driver');

        if (!empty($id_driver)) {

            $sql = DB::table('tj_requete')
                ->leftJoin('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
                ->Join('tj_conducteur', 'tj_conducteur.id', '=', 'tj_requete.id_conducteur')
                ->Join('tj_payment_method', 'tj_payment_method.id', '=', 'tj_requete.id_payment_method')
                ->Join('bookingtypes', 'tj_requete.booking_type_id', '=', 'bookingtypes.id')
                ->select(
                    'tj_requete.id',
                    'tj_requete.id_user_app',
                    'tj_requete.distance_unit',
                    'tj_requete.depart_name',
                    'tj_requete.destination_name',
                    'tj_requete.otp',
                    'tj_requete.latitude_depart',
                    'tj_requete.longitude_depart',
                    'tj_requete.latitude_arrivee',
                    'tj_requete.longitude_arrivee',
                    'tj_requete.number_poeple',
                    'tj_requete.place',
                    'tj_requete.statut',
                    'tj_requete.id_conducteur',
                    'tj_requete.creer',
                    'tj_requete.trajet',
                    'tj_requete.feel_safe_driver',
                    'tj_user_app.nom',
                    'tj_user_app.prenom',
                    'tj_user_app.id as existing_user_id',
                    'tj_requete.distance',
                    'tj_requete.ride_type',
                    'tj_user_app.phone',
                    'tj_user_app.photo_path',
                    'tj_conducteur.nom as nomConducteur',
                    'tj_conducteur.prenom as prenomConducteur',
                    'tj_conducteur.phone as driverPhone',
                    'tj_requete.date_retour',
                    'tj_requete.heure_retour',
                    'tj_requete.statut_round',
                    'tj_requete.montant',
                    'tj_requete.duree',
                    'tj_user_app.id as userId',
                    'tj_requete.statut_paiement',
                    'tj_payment_method.libelle as payment',
                    'tj_payment_method.image as payment_image',
                    'tj_requete.trip_objective',
                    'tj_requete.age_children1',
                    'tj_requete.age_children2',
                    'tj_requete.age_children3',
                    'tj_requete.stops',
                    'tj_requete.tax',
                    'tj_requete.tip_amount',
                    'tj_requete.discount',
                    'tj_requete.admin_commission',
                    'tj_requete.user_info',
                    'tj_requete.vehicle_id',
                    'tj_requete.bookfor_others_mobileno',
                    'tj_requete.bookfor_others_name',
                    'bookingtypes.bookingtype',
                    'tj_requete.ride_required_on_date',
                    'tj_requete.ride_required_on_time'
                )
                ->where('tj_requete.id_conducteur', '=', $id_driver)
                ->where('tj_requete.vehicle_Id', '!=', '')
                ->orderBy('tj_requete.id', 'desc')
                ->get();

            foreach ($sql as $row) {
                $row->id = (string) $row->id;
                $row->stops = json_decode($row->stops, true);
                $row->tax = json_decode($row->tax, true);
                $row->user_info = json_decode($row->user_info, true);
                $row->consumer_name = $row->nom . ' ' . $row->prenom;

                $row->bookfor_others_mobileno = $row->bookfor_others_mobileno;
                $row->bookfor_others_name = $row->bookfor_others_name;
                $row->userId = (string) $row->userId;
                $id_user_app = $row->id_user_app;
                $lat = $row->latitude_depart;
                $long = $row->longitude_depart;
                $ride_id = $row->id;
                if ($row->otp == null) {
                    $row->otp = "";
                }
                if ($row->ride_type == "dispatcher") {

                    $row->ride_type = "dispatcher";

                }else if($row->ride_type=="driver"){

                    $row->ride_type = "driver";

                }else{

                    $row->ride_type = "normal";
                }

                if ($id_user_app != 0) {

                    $sql_cond = DB::table('tj_conducteur')
                        ->select('nom as nomConducteur', 'prenom as prenomConducteur')
                        ->where('id', '=', $id_driver)
                        ->get();

                    foreach ($sql_cond as $row_cond) {
                        $row->nomConducteur = $row_cond->nomConducteur;
                        $row->prenomConducteur = $row_cond->prenomConducteur;
                    }

                    // Nb avis conducteur
                    $sql_nb_avis = DB::table('tj_note')
                        ->select(DB::raw("COUNT(id) as nb_avis"), DB::raw("SUM(niveau) as somme"))
                        ->where('id_conducteur', '=', $id_driver)
                        ->get();

                    if (!empty($sql_nb_avis)) {
                        foreach ($sql_nb_avis as $row_nb_avis) {
                            $somme = $row_nb_avis->somme;
                            $nb_avis = $row_nb_avis->nb_avis;
                            if ($nb_avis != "0")
                                $moyenne = $somme / $nb_avis;
                            else
                                $moyenne = 0;
                        }
                    } else {
                        $somme = "0";
                        $nb_avis = "0";
                        $moyenne = 0;
                    }

                    $sql_nb_avis_driver = DB::table('tj_user_note')
                        ->select(DB::raw("COUNT(id) as nb_avis_driver"), DB::raw("SUM(niveau_driver) as somme_driver"))
                        ->where('id_user_app', '=', $id_user_app)
                        ->get();

                    if (!empty($sql_nb_avis_driver)) {
                        foreach ($sql_nb_avis_driver as $row_nb_avis_driver) {
                            $somme_driver = $row_nb_avis_driver->somme_driver;
                            $nb_avis_driver = $row_nb_avis_driver->nb_avis_driver;
                            if ($nb_avis_driver != "0")
                                $moyenne_driver = $somme_driver / $nb_avis_driver;
                            else
                                $moyenne_driver = 0;
                        }
                    } else {
                        $somme_driver = "0";
                        $nb_avis_driver = "0";
                        $moyenne_driver = 0;
                    }

                    // Note conducteur
                    $sql_note = DB::table('tj_note')
                        ->select('niveau', 'comment')
                        ->where('id_user_app', '=', $id_user_app)
                        ->where('id_conducteur', '=', $id_driver)
                        ->get();

                    foreach ($sql_note as $row_note) {
                        if (!empty($row_note)) {
                            $row->comment = $row_note->comment;
                        } else {
                            $row->comment = "";
                        }
                    }

                    // Note user
                    $sql_note_driver = DB::table('tj_user_note')
                        ->select('niveau_driver', 'comment')
                        ->where('id_user_app', '=', $id_user_app)
                        ->where('id_conducteur', '=', $id_driver)
                        ->get();

                    foreach ($sql_note_driver as $row_note_driver) {
                        if (!empty($row_note_driver)) {
                            $row->comment_driver = $row_note_driver->comment;
                        } else {
                            $row->comment_driver = "";
                        }
                    }

                    $sql_phone = DB::table('tj_conducteur')
                        ->select('phone')
                        ->where('id', '=', $id_driver)
                        ->get();
                    foreach ($sql_phone as $row_phone) {
                        $row->driver_phone = $row_phone->phone;
                    }

                    $row->moyenne = number_format((float) $moyenne, 1);
                    $row->moyenne_driver = number_format((float) $moyenne_driver, 1);

                } else {
                    $row->nomConducteur = "";
                    $row->prenomConducteur = "";
                    $row->moyenne = "0.0";
                    $row->driver_phone = "";
                    $row->moyenne_driver = "0.0";
                }

                // $sql_vehicle = DB::table('tj_vehicule')
                //     ->select('*')
                //     ->where('id_conducteur', '=', $id_driver)
                //     ->get();

                // foreach ($sql_vehicle as $row_vehicle) {
                //     $row->idVehicule = (string) $row_vehicle->id;
                //     $row->brand = $row_vehicle->brand;
                //     $row->model = $row_vehicle->model;
                //     $row->car_make = $row_vehicle->car_make;
                //     $row->milage = $row_vehicle->milage;
                //     $row->km = $row_vehicle->km;
                //     $row->color = $row_vehicle->color;
                //     $row->numberplate = $row_vehicle->numberplate;
                //     $row->passenger = $row_vehicle->passenger;
                // }

                $sql_vehicle = DB::table('tj_vehicule')
                    ->join('brands', 'brands.id', '=', 'tj_vehicule.brand')
                    ->join('car_model', 'car_model.id', '=', 'tj_vehicule.model')
                    ->select('tj_vehicule.id','brands.name as brandname','car_model.name as modelname','tj_vehicule.numberplate','tj_vehicule.primary_image_id','tj_vehicule.color')
                    ->where('tj_vehicule.id', '=', $row->vehicle_id)
                    ->get();
                    
                foreach ($sql_vehicle as $row_vehicle) {
                    $row->idVehicule = (string) $row_vehicle->id;
                    $row->brand = $row_vehicle->brandname;
                    $row->model = $row_vehicle->modelname;
                    // $row->car_make = $row_vehicle->car_make;
                    // $row->milage = $row_vehicle->milage;
                    // $row->km = $row_vehicle->km;
                    $row->color = $row_vehicle->color;
                    $row->numberplate = $row_vehicle->numberplate;
                    

                    if ($row_vehicle->primary_image_id != '') {
                        if (file_exists(public_path('assets/images/vehicle' . '/' . $row_vehicle->primary_image_id))) {
                            $vechicle_image = asset('assets/images/vehicle') . '/' . $row_vehicle->primary_image_id;
                        } else {
                            $vechicle_image = asset('assets/images/placeholder_image.jpg');
    
                        }
                        $row->vehicle_image = $vechicle_image;
                    }
                }

                $currentDateTime = Carbon::now();

                // if ($row->statut == 'vehicle assigned') {
                //     if ($trip_accept_reject_driver_time_sec != '') {
                //         $rideData = Requests::find($row->id);
                //         $rejectDriverIds = $rideData->rejected_driver_id;
                //         $rejDriverIds = array();
                //         if ($rejectDriverIds != null) {
                //             $rejDriverIds = json_decode($rejectDriverIds, true);
                //         }
                //         $seconds = $trip_accept_reject_driver_time_sec;
                //         if(sizeof($rejDriverIds)>0){
                //             $seconds = $trip_accept_reject_driver_time_sec + (sizeof($rejDriverIds) * $trip_accept_reject_driver_time_sec);
                //         }
                //         $date = Date("Y-m-d H:i:s", strtotime("$seconds seconds", strtotime($row->creer)));
                       
                //         if ($currentDateTime > $date) {

                //             $rideData->statut = "canceled";
                //             $rideData->save();

                //             $row->statut = "canceled";

                //             $title = str_replace("'", "\'", "Canceled your ride");
                //             $msg = str_replace("'", "\'", $row->nomConducteur . " " . $row->prenomConducteur . " is Canceled your ride.");

                //             $tab[] = array();
                //             $tab = explode("\\", $msg);
                //             $msg_ = "";
                //             for ($i = 0; $i < count($tab); $i++) {
                //                 $msg_ = $msg_ . "" . $tab[$i];
                //             }
                //             $message = array("body" => $msg_, "title" => $title, "sound" => "mySound", "tag" => "ridecanceled");

                            // $query = DB::table('tj_user_app')
                            //     ->select('fcm_id')
                            //     ->where('fcm_id', '!=', NULL)
                            //     ->where('id', '=', $row->userId)
                            //     ->get();

                //             $tokens = array();
                //             if (!empty($query)) {
                //                 foreach ($query as $user) {
                //                     if (!empty($user->fcm_id)) {
                //                         $tokens[] = $user->fcm_id;
                //                     }
                //                 }
                //             }
                //             $temp = array();
                //             $data = $rideData->toArray();
                //             if (count($tokens) > 0) {
                //                 GcmController::send_notification($tokens, $message, $data);

                //             }

                //             $vehicleType = DB::table('tj_vehicule')->select('id_type_vehicule')->where('id_conducteur', $id_driver)->first();
                //             $settings = DB::table('tj_settings')->select('driver_radios', 'minimum_deposit_amount')->first();
                //             $radius = $settings->driver_radios;
                //             $minimum_wallet_balance = $settings->minimum_deposit_amount;
                //             $data = DB::table("tj_conducteur")
                //                 // ->join('tj_vehicule', 'tj_vehicule.id_conducteur', '=', 'tj_conducteur.id')
                //                 ->select(
                //                     "tj_conducteur.id"
                //                     , DB::raw("3959  * acos(cos(radians(" . $lat . "))
                //             * cos(radians(tj_conducteur.latitude))
                //             * cos(radians(tj_conducteur.longitude) - radians(" . $long . "))
                //             + sin(radians(" . $lat . "))
                //             * sin(radians(tj_conducteur.latitude))) AS distance")
                //                 )
                //                 ->having('distance', '<=', $radius)
                //                 ->distinct('tj_conducteur.id')
                //                 ->orderBy('distance', 'asc')
                //                 ->where('tj_conducteur.statut', 'yes')
                //                 ->where('tj_conducteur.id', '!=', $id_driver)
                //                 ->whereNotIn('tj_conducteur.id', $rejDriverIds)
                //                 ->where('tj_conducteur.amount', '>=', $minimum_wallet_balance)
                //                 ->where('tj_conducteur.is_verified', '=', '1')
                //                 ->where('tj_conducteur.online', '!=', 'no')
                //                 // ->where('id_type_vehicule', '=', $vehicleType->id_type_vehicule)
                //                 ->first();

                //             if (!empty($data)) {
                               
                //                     $id = $data->id;
                //                     $title = str_replace("'", "\'", "New ride");
                //                     $msg = str_replace("'", "\'", "You have just received a request from a client");

                //                     $tab[] = array();
                //                     $tab = explode("\\", $msg);
                //                     $msg_ = "";
                //                     for ($i = 0; $i < count($tab); $i++) {
                //                         $msg_ = $msg_ . "" . $tab[$i];
                //                     }

                //                     $message = array("body" => $msg_, "title" => $title, "sound" => "mySound", "tag" => "ridenewrider");

                //                     $query = DB::table('tj_conducteur')
                //                         ->select('fcm_id')
                //                         ->where('fcm_id', '<>', '')
                //                         ->where('id', '=', $id)
                //                         ->get();

                //                     $tokens = array();
                //                     if ($query->count() > 0) {
                //                         foreach ($query as $user) {
                //                             if (!empty($user->fcm_id)) {
                //                                 $tokens[] = $user->fcm_id;
                //                             }
                //                         }
                //                     }

                //                     $temp = array();
                //                     $data = $rideData->toArray();
                //                     if (count($tokens) > 0) {
                //                         GcmController::send_notification($tokens, $message, $data);
                //                     }
                //                     if ($id) {
                //                         $row->statut = "vehicle assigned";
                //                         if(!in_array($id_driver,$rejDriverIds)){
                //                             array_push($rejDriverIds, $id_driver);

                //                         }
                //                         $updateRejDriverArr = json_encode($rejDriverIds);
                //                         $updatedata = DB::update('update tj_requete set statut = ?,id_conducteur = ?,rejected_driver_id=? where id = ?', ['new', $id, $updateRejDriverArr, $ride_id]);
                //                     }
                                
                //             } else {
                //                 $row->statut = "driver rejected";
                //                 if (!in_array($id_driver, $rejDriverIds)) {
                //                     array_push($rejDriverIds, $id_driver);

                //                 }
                //                 $updateRejDriverArr = json_encode($rejDriverIds);
                //                 $updatedata = DB::update('update tj_requete set statut = ?,rejected_driver_id=? where id = ?', ['driver rejected', $updateRejDriverArr, $ride_id]);

                //             }


                //         }

                //     }

                // }

                $row->creer = date("d", strtotime($row->creer)) . " " . $months[date("F", strtotime($row->creer))] . ", " . date("Y", strtotime($row->creer));
                $row->date_retour = date("d", strtotime($row->date_retour)) . " " . $months[date("F", strtotime($row->date_retour))] . ", " . date("Y", strtotime($row->date_retour));

                $row->ride_required_on_date = date("d", strtotime($row->ride_required_on_date)) . " " . $months[date("F", strtotime($row->ride_required_on_date))] . ", " . date("Y", strtotime($row->ride_required_on_date))." ". $row->ride_required_on_time;
                

                if ($row->photo_path != '') {
                    if (file_exists(public_path('assets/images/users' . '/' . $row->photo_path))) {
                        $image_user = asset('assets/images/users') . '/' . $row->photo_path;
                    } else {
                        $image_user = asset('assets/images/placeholder_image.jpg');

                    }
                    $row->photo_path = $image_user;
                }
                if ($row->payment_image != '') {
                    if (file_exists(public_path('assets/images/payment_method' . '/' . $row->payment_image))) {
                        $image = asset('assets/images/payment_method') . '/' . $row->payment_image;
                    } else {
                        $image = asset('assets/images/placeholder_image.jpg');

                    }
                    $row->payment_image = $image;
                }

                $output[] = $row;

            }

            if (!empty($output)) {

                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'successfully';
                $response['data'] = $output;
            } else {
                $response['success'] = 'Failed';
                $response['error'] = 'Failed to fetch data';
            }

        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'Some Fields are missing';
        }
        return response()->json($response);


    }

}