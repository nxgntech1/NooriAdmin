<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\API\v1\GcmController;
use App\Http\Controllers\Controller;
use App\Models\Requests;
use DB;
use PDO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class RequeteRegisterController extends Controller
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

    public function register(Request $request)
    {

        $user_id = $request->get('user_id');
        $ride_type = $request->get('ride_type');
        $user_detail = json_encode($request->get('user_detail'));
        $lat1 = $request->get('lat1');
        $lng1 = $request->get('lng1');
        $lat2 = $request->get('lat2');
        $lng2 = $request->get('lng2');
        $cout = $request->get('cout');
        $duree = $request->get('duree');
        $distance = $request->get('distance');
        $distance_unit = $request->get('distance_unit');

        $age_children1 = $request->get('age_children1');
        $age_children2 = $request->get('age_children2');
        $age_children3 = $request->get('age_children3');
        $trip_objective = $request->get('trip_objective');
        $trip_category = $request->get('trip_category');
        $id_conducteur = $request->get('id_conducteur');
        
        $id_payment = $request->get('id_payment');
        $depart_name = $request->get('depart_name');
        $destination_name = $request->get('destination_name');
        $image = $request->file('image');
        $place = $request->get('place');
        $place = str_replace("'", "\'", $place);
        $number_poeple = $request->get('number_poeple');
        $number_poeple = str_replace("'", "\'", $number_poeple);
        $statut_round = $request->get('statut_round');
        $stops=json_encode($request->get('stops'));
        if (!empty($request->get('date_retour')))
            $date_retour = $request->get('date_retour');
        else
            $date_retour = date('Y-m-d');
        if (!empty($request->get('heure_retour')))
            $heure_retour = $request->get('heure_retour');
        else
            $heure_retour = date('H:i:s');
        $date_heure = date('Y-m-d H:i:s');

        if (!empty($image)) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $time = time() . '.' . $extenstion;
            $filename = 'requete_images_' . $time;
            $file->move(public_path('images/recu_trajet_course/'), $filename);
        } else {
            $filename = '';
        }
        if (!empty($id_payment)) {
            if ($ride_type != "driver") {

                $date_heure = date('Y-m-d H:i:s');

                $insertdata = DB::insert("insert into tj_requete(date_retour,statut_round,heure_retour,
            number_poeple,place,id_payment_method,trajet,depart_name,
            destination_name,id_conducteur,id_user_app,latitude_depart,longitude_depart,latitude_arrivee,
            longitude_arrivee,statut,creer,distance,distance_unit,montant,duree,trip_objective,age_children1,
            age_children2,age_children3,feel_safe,tip_amount,statut_paiement,
            modifier,statut_course,id_conducteur_accepter,trip_category,feel_safe_driver,stops)
        values('" . $date_retour . "','" . $statut_round . "','" . $heure_retour . "','" . $number_poeple . "','" . $place . "','" . $id_payment . "','" . $filename . "','" . $depart_name . "','" . $destination_name . "'
        ,'" . $id_conducteur . "','" . $user_id . "','" . $lat1 . "','" . $lng1 . "','" . $lat2 . "','" . $lng2 . "',
        'new','" . $date_heure . "','" . $distance . "', '" . $distance_unit . "', '" . $cout . "','" . $duree . "',
        '" . $trip_objective . "','" . $age_children1 . "','" . $age_children2 . "',
        '" . $age_children3 . "',0,0,'','" . $date_heure . "','',0,'',0,'" . $stops . "')");

                $id = DB::getPdo()->lastInsertId();
                if ($id > 0) {
                    $get_user = Requests::where('id', $id)->first();
                    $rowData = $get_user->toArray();
                }

                $tmsg = '';
                $terrormsg = '';

                $title = str_replace("'", "\'", "New ride");
                $msg = str_replace("'", "\'", "You have just received a request from a client");

                $tab[] = array();
                $tab = explode("\\", $msg);
                $msg_ = "";
                for ($i = 0; $i < count($tab); $i++) {
                    $msg_ = $msg_ . "" . $tab[$i];
                }
                $message = array("body" => $msg_, "title" => $title, "sound" => "mySound", "tag" => "ridenewrider");

                $query = DB::table('tj_conducteur')
                    ->select('fcm_id')
                    ->where('fcm_id', '<>', '')
                    ->where('id', '=', $id_conducteur)
                    ->get();

                $tokens = array();
                if ($query->count() > 0) {
                    foreach ($query as $user) {
                        if (!empty($user->fcm_id)) {
                            $tokens[] = $user->fcm_id;
                        }
                    }
                }

                $temp = array();
                $data = $rowData;
                if (count($tokens) > 0) {
                    GcmController::send_notification($tokens, $message,$data);
                }


            } else {
                $date_heure = date('Y-m-d H:i:s');

                $insertdata = DB::insert("insert into tj_requete(date_retour,statut_round,heure_retour,
            number_poeple,place,id_payment_method,trajet,depart_name,
            destination_name,id_conducteur,id_user_app,latitude_depart,longitude_depart,latitude_arrivee,
            longitude_arrivee,statut,creer,distance,distance_unit,montant,duree,trip_objective,age_children1,
            age_children2,age_children3,feel_safe,tip_amount,statut_paiement,
            modifier,statut_course,id_conducteur_accepter,trip_category,feel_safe_driver,stops,ride_type,user_info)
            values('" . $date_retour . "','" . $statut_round . "','" . $heure_retour . "','" . $number_poeple . "','" . $place . "','" . $id_payment . "','" . $filename . "','" . $depart_name . "','" . $destination_name . "'
            ,'" . $id_conducteur . "','" . $user_id . "','" . $lat1 . "','" . $lng1 . "','" . $lat2 . "','" . $lng2 . "',
            'confirmed','" . $date_heure . "','" . $distance . "', '" . $distance_unit . "', '" . $cout . "','" . $duree . "',
            '" . $trip_objective . "','" . $age_children1 . "','" . $age_children2 . "',
            '" . $age_children3 . "',0,0,'','" . $date_heure . "','',0,'',0,'" . $stops . "','" . $ride_type . "','" . $user_detail . "')");


                $id = DB::getPdo()->lastInsertId();

            }
            if ($id > 0) {
                $get_user = Requests::where('id', $id)->first();
                $row = $get_user->toArray();
                
                $row['stops']=json_decode($row['stops'],true);
                $row['user_info'] = json_decode($row['user_info'], true);
                if ($row['trajet'] != '') {
                    if (file_exists(public_path('images/recu_trajet_course/' . '/' . $row['trajet']))) {
                        $image_user = asset('images/recu_trajet_course/') . '/' . $row['trajet'];
                    } else {
                        $image_user = asset('assets/images/placeholder_image.jpg');

                    }
                    $row['trajet'] = $image_user;
                }

                $output[] = $row;
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'Successfully created';
                $response['data'] = $output;
            } else {
                $response['success'] = 'Failed';
                $response['error'] = 'Failed';
            }
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'some field required';
        }


        return response()->json($response);
    }

     
    public function BookRide(Request $request)
    {

        $user_id = $request->get('user_id');
        $car_model_id = $request->get('car_model_id');
        //$user_detail = json_encode($request->get('user_detail'));
        $finalAmont = $request->get('finalAmount');
        $lat1 = $request->get('lat1');
        $lng1 = $request->get('lng1');
        $lat2 = $request->get('lat2');
        $lng2 = $request->get('lng2');
        $depart_name = $request->get('depart_name');
        $destination_name = $request->get('destination_name');
        $booking_type_id = $request->get('booking_type_id');
        $duree = $request->get('duration');
        $distance = $request->get('distance');
        $distance_unit = $request->get('distance_unit');
        $id_payment = $request->get('id_payment_method');
        $ride_date = $request->get('ride_date');
        $ride_time = $request->get('ride_time');
        if (!empty($request->get('bookfor_others_mobileno')))
        {
            $bookfor_others_mobileno = $request->get('bookfor_others_mobileno');
            $bookfor_others_name = $request->get('bookfor_others_name');
        }
        else
        {
            $bookfor_others_mobileno = "";
            $bookfor_others_name = "";
        }

        if (!empty($request->get('coupon_id')))
            $coupon_id = $request->get('coupon_id');
        else
            $coupon_id = '0';

        // $cout = $request->get('cout');
        // $age_children1 = $request->get('age_children1');
        // $age_children2 = $request->get('age_children2');
        // $age_children3 = $request->get('age_children3');
        // $trip_objective = $request->get('trip_objective');
        // $trip_category = $request->get('trip_category');
        // $id_conducteur = $request->get('id_conducteur');
       // $id_payment = $request->get('id_payment');
        
        //$image = $request->file('image');
        // $place = $request->get('place');
        // $place = str_replace("'", "\'", $place);
        // $number_poeple = $request->get('number_poeple');
        // $number_poeple = str_replace("'", "\'", $number_poeple);
        //$statut_round = $request->get('statut_round');
        //$stops=json_encode($request->get('stops'));
       

        // if (!empty($request->get('heure_retour')))
        //     $heure_retour = $request->get('heure_retour');
        // else
        //     $heure_retour = date('H:i:s');
        // $date_heure = date('Y-m-d H:i:s');

        // // // // // // // // // // // if (!empty($image)) {
        // // // // // // // // // // //     $file = $request->file('image');
        // // // // // // // // // // //     $extenstion = $file->getClientOriginalExtension();
        // // // // // // // // // // //     $time = time() . '.' . $extenstion;
        // // // // // // // // // // //     $filename = 'requete_images_' . $time;
        // // // // // // // // // // //     $file->move(public_path('images/recu_trajet_course/'), $filename);
        // // // // // // // // // // // } else {
        // // // // // // // // // // //     $filename = '';
        // // // // // // // // // // // }


        // Need to get the info from database
            // Amount details
            // Tax Details
            // Discount Details 
            
            //$cout = 500;

        if (!empty($id_payment)) {
           
                $date_heure = date('Y-m-d H:i:s');

                $insertdata = DB::insert("insert into tj_requete(ride_required_on_date,
                ride_required_on_time,
                model_id,
                id_payment_method,
                booking_type_id,
                depart_name,
                destination_name,
                id_user_app,
                bookfor_others_mobileno,
                bookfor_others_name,
                latitude_depart,
                longitude_depart,
                latitude_arrivee,
                longitude_arrivee,
                statut,
                creer,
                distance,
                distance_unit,
                montant,
                duree,
                feel_safe,
                tip_amount,
                statut_paiement,
                modifier)
                values('" . $ride_date . "',
                '" . $ride_time . "',
                '" . $car_model_id . "',
                '" . $id_payment . "',
                '" . $booking_type_id . "',
                '" . $depart_name . "',
                '" . $destination_name . "',
                '" . $user_id . "',
                '" . $bookfor_others_mobileno . "',
                '" . $bookfor_others_name . "',
                '" . $lat1 . "',
                '" . $lng1 . "',
                '" . $lat2 . "',
                '" . $lng2 . "',
                '',
                '" . $date_heure . "',
                '" . $distance . "',
                '" . $distance_unit . "',
                '" . $finalAmont . "',
                '" . $duree . "',
                0,
                0,
                '',
                '" . $date_heure . "')");

                $id = DB::getPdo()->lastInsertId();

                // if ($id > 0) {
                //     $get_user = Requests::where('id', $id)->first();
                //     $rowData = $get_user->toArray();
                // }

                // // // // // $tmsg = '';
                // // // // // $terrormsg = '';

                // // // // // $title = str_replace("'", "\'", "New ride");
                // // // // // $msg = str_replace("'", "\'", "You have just received a request from a client");

                // // // // // $tab[] = array();
                // // // // // $tab = explode("\\", $msg);
                // // // // // $msg_ = "";
                // // // // // for ($i = 0; $i < count($tab); $i++) {
                // // // // //     $msg_ = $msg_ . "" . $tab[$i];
                // // // // // }
                // // // // // $message = array("body" => $msg_, "title" => $title, "sound" => "mySound", "tag" => "ridenewrider");

                // // // // // $query = DB::table('tj_conducteur')
                // // // // //     ->select('fcm_id')
                // // // // //     ->where('fcm_id', '<>', '')
                // // // // //     ->where('id', '=', $id_conducteur)
                // // // // //     ->get();

                // // // // // $tokens = array();
                // // // // // if ($query->count() > 0) {
                // // // // //     foreach ($query as $user) {
                // // // // //         if (!empty($user->fcm_id)) {
                // // // // //             $tokens[] = $user->fcm_id;
                // // // // //         }
                // // // // //     }
                // // // // // }

                // // // // // $temp = array();
                // // // // // $data = $rowData;
                // // // // // if (count($tokens) > 0) {
                // // // // //     GcmController::send_notification($tokens, $message,$data);
                // // // // // }


            
            if ($id > 0) {

                    
                $pdo = DB::getPdo();
                $stmt = $pdo->prepare('CALL update_ride_details(:ride_id,:coupon_id,@intout)');
                $stmt->bindParam(':ride_id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':coupon_id', $coupon_id, PDO::PARAM_INT);
                $stmt->execute();

                $stmt = $pdo->query('SELECT @intout as INTRETURN');
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $intout = $result['INTRETURN'];

                // $get_user = Requests::where('id', $id)->first();
                // $row = $get_user->toArray();
                
                // $row['stops']=json_decode($row['stops'],true);
                // $row['user_info'] = json_decode($row['user_info'], true);
                // if ($row['trajet'] != '') {
                //     if (file_exists(public_path('images/recu_trajet_course/' . '/' . $row['trajet']))) {
                //         $image_user = asset('images/recu_trajet_course/') . '/' . $row['trajet'];
                //     } else {
                //         $image_user = asset('assets/images/placeholder_image.jpg');

                //     }
                //     $row['trajet'] = $image_user;
                // }

            }
            if (!empty($intout)){
                if ($intout = "1")
                {
                    //$output[] = $row;
                    $response['ride_id'] = $id;
                    $response['success'] = 'success';
                    $response['error'] = null;
                    $response['message'] = 'Successfully created';
                }else{
                    $response['success'] = 'Failed';
                    $response['error'] = 'Failed';    
                }
            } else {
                $response['success'] = 'Failed';
                $response['error'] = 'Failed';
            }
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'some field required';
        }


        return response()->json($response);
    }

    /*Payment Confirmation  */
    public function updateRideStatus(Request $request)
    {
        $id_user = $request->get('user_id');
        $ride_id = $request->get('ride_id');
        $transaction_id = $request->get('transaction_id');
        $date_heure = date('Y-m-d H:i:s');

        if(!empty($id_user) && !empty($ride_id) && !empty($transaction_id)){

            $updatedata = DB::table('TJ_REQUETE')
            ->where('id', $ride_id)
            ->where('id_user_app', $id_user)
            ->update(['transaction_id' => $transaction_id,'modifier' => $date_heure ,'statut' => 'new']);
            
            if (!empty($updatedata)) {
                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'status successfully updated';
                $response['data'] = '1';
            } else {
            $response['success'] = 'Failed';
            $response['error'] = 'failed to update';
            }
        } else{
            $response['success'] = 'Failed';
            $response['error'] = 'some field are missing';
        }
        return response()->json($response);

    }

}
