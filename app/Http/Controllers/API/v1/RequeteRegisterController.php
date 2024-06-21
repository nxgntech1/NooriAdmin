<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\API\v1\GcmController;
use App\Http\Controllers\API\v1\NotificationsController;
use App\Http\Controllers\Controller;
use App\Models\Requests;
use App\Services\FcmService;
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
        $brand_Id = $request->get('car_brand_id');
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
        $otp = random_int(1000, 9999);

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
           
                if ($id_payment = '5')
                    $ridestatus = 'new';

                $date_heure = date('Y-m-d H:i:s');

                $insertdata = DB::insert("insert into tj_requete(ride_required_on_date,
                ride_required_on_time,
                model_id,
                brand_id,
                id_payment_method,
                otp,
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
                '" . $brand_Id . "',
                '" . $id_payment . "',
                '" . $otp . "',
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
                '" . $ridestatus . "',
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

                // $temp = array();
                // $data = $rowData;
                // if (count($tokens) > 0) {
                     //GcmController::send_notification($tokens, $message,$data);
                    
                     
                // }

             
            
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


                // if ($id_payment = '5')
                // {
                    
                 $EmailResponse = $this->SendBookRideEmailNotifiaction($id);

                // // // //     $tmsg = '';
                // // // //     $terrormsg = '';

                // // // //     $title = str_replace("'", "\'", "New ride");
                // // // //     $msg = str_replace("'", "\'", "You have just received a request from a client");
                
                // // // //     $tab[] = array();
                // // // //     $tab = explode("\\", $msg);
                // // // //     $msg_ = "";
                // // // //     for ($i = 0; $i < count($tab); $i++) {
                // // // //         $msg_ = $msg_ . "" . $tab[$i];
                // // // //     }

                // // // //     // if ($id > 0) {
                // // // //     //     $get_user = Requests::where('id', '140')->first();
                // // // //     //     $rowData = $get_user->toArray();
                // // // //     // // }

                // // // //     $tokens = 'cC9i9w3_S9-MdNPhCAUoFf:APA91bHE3_RsMUm5Y_UXN9dvcC7ALUgk7DfsTj5mEGAyLIDaHsZZtTKm_LibaAyTKRvMWhzfq_Q9f28jB8vPLpJOa62saag7Gd4wE4dm_GZrVca3XFPyNFS7AAJI8Lvgi4KRQNf7GgiD';
                // // // // // $data = $rowData;

                
                // // // //     $message1 = [
                // // // //         'title' => $title,
                // // // //         'body' => $msg_,
                // // // //         'sound'=> 'mySound',
                // // // //         'tag' => 'ridenewrider'
                // // // //     ];



                // // // //     $notifications= new NotificationsController();
                // // // //     $notifcationres = $notifications->sendNotification($tokens, $message1,null);

                    //$SMS_Notifiaction = $notifications->sendSMS('919885084010','OTP is for 3456 TeamPlay app. Do not share the OTP with anyone for security reasons');
                //} 


            }
            if (!empty($intout)){
                if ($intout = "1")
                {
                    //$output[] = $row;
                    $response['ride_id'] = $id;
                    $response['success'] = 'success';
                    $response['error'] = null;
                    $response['message'] = 'Successfully created';
                    //$response['EmailResponse'] = $EmailResponse;
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

    public function SendBookRideEmailNotifiaction($ride_id)
    {
        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $sql = DB::table('tj_requete')
        ->Join('tj_user_app', 'tj_user_app.id', '=', 'tj_requete.id_user_app')
        ->Join('car_model', 'car_model.id', '=', 'tj_requete.model_id')
        ->Join('brands', 'brands.id', '=', 'tj_requete.brand_id')
        ->Join('tj_payment_method', 'tj_payment_method.id', '=', 'tj_requete.id_payment_method')
        ->Join('bookingtypes', 'tj_requete.booking_type_id','=','bookingtypes.id')
        ->select('tj_requete.id','tj_requete.id_user_app', 'tj_requete.depart_name',
            'tj_requete.distance_unit', 'tj_requete.destination_name', 'tj_requete.latitude_depart',
            'tj_requete.longitude_depart', 'tj_requete.latitude_arrivee', 'tj_requete.longitude_arrivee',
            'tj_requete.statut', 'tj_requete.id_conducteur',
            'tj_requete.creer', 'tj_requete.tax_amount','tj_requete.discount',
            'tj_user_app.nom', 'tj_user_app.prenom', 'tj_requete.otp','tj_user_app.email as customeremail',
            'tj_requete.distance', 'tj_user_app.phone','tj_requete.date_retour', 'tj_requete.heure_retour',
            'tj_requete.montant', 'tj_requete.duree', 'tj_requete.statut_paiement',
            'tj_requete.car_Price','tj_requete.sub_total',
            'tj_requete.ride_required_on_date','tj_requete.ride_required_on_time','tj_requete.bookfor_others_mobileno','tj_requete.bookfor_others_name',
            'tj_requete.vehicle_Id','tj_requete.id_conducteur','car_model.name as carmodel','brands.name as brandname',
            'tj_payment_method.libelle as payment', 'tj_payment_method.image as payment_image','tj_requete.id_payment_method as paymentmethodid', 'bookingtypes.bookingtype as bookingtype')
            ->where('tj_requete.id', '=', $ride_id)
            ->get();

            //$response['EmailResponseSql'] = json_encode($sql,JSON_PRETTY_PRINT);

            foreach ($sql as $row) {

            //if (!empty($row->customeremail)) {

                $emailsubject = '';
                $emailmessage = '';
               // $emailtemplate = DB::table('email_template')->select('*')->where('type', 'newride_to_consumer')->first();

                // if (!empty($emailtemplate)) {
                    $emailsubject = "Your Booking is Confirmed!";
                    $emailmessage = file_get_contents(resource_path('views/emailtemplates/confirmbooking.html'));
                    //$send_to_admin = $emailtemplate->send_to_admin;
                //}

            
               
                $currency = DB::table('tj_currency')->select('*')->where('statut', 'yes')->first();

             
                $customer_name = $row->nom;
                $carmodelandbrand = $row->brandname .' / '. $row->carmodel;
                $pickup_Location = $row->depart_name;
                $drop_Location = $row->destination_name;
                $booking_date = date("d", strtotime($row->creer)) . " " . $months[date("F", strtotime($row->creer))] . ", " . date("Y", strtotime($row->creer));
                $booking_time = date("h:m A", strtotime($row->creer)); 
                $payment_method = $row->payment;
                $bookingtype = $row->bookingtype;
                $pickupdate = date("d", strtotime($row->ride_required_on_date)) . " " . $months[date("F", strtotime($row->ride_required_on_date))] . ", " . date("Y", strtotime($row->ride_required_on_date)); 
                $pickuptime = date("h:m A", strtotime($row->ride_required_on_time));
                $brandname = $row->brandname;
                
                
                $car_Price = $row->car_Price;
                if(!empty($car_Price))
                    $car_Price = $currency->symbole . "" . number_format($row->car_Price,$currency->decimal_digit);

                $coupon_discount = $row->discount;
                if(!empty($coupon_discount))
                    $coupon_discount = $currency->symbole . "" . number_format($coupon_discount,$currency->decimal_digit);
                else
                    $coupon_discount = $currency->symbole . "" . number_format($coupon_discount,$currency->decimal_digit); 

                $tax_amount = $row->tax_amount;
                if(!empty($tax_amount))
                    $tax_amount = $currency->symbole . "" . number_format($tax_amount,$currency->decimal_digit);

                $sub_total = $row->sub_total;
                if(!empty($sub_total))
                    $sub_total = $currency->symbole . "" . number_format($sub_total,$currency->decimal_digit);

                $final_amount = $row->montant;
                if(!empty($sub_tfinal_amountotal))
                    $final_amount = $currency->symbole . "" . number_format($final_amount,$currency->decimal_digit);


                $app_name = env('APP_NAME', 'Noori Travels');
                $to = 'govind.p.raj@gmail.com';//env('ADMIN_EMAILID', 'govind.p.raj@gmail.com');
    
                $emailmessage = str_replace("{AppName}", $app_name, $emailmessage);
                $emailmessage = str_replace("{CustomerName}", $customer_name, $emailmessage);
                $emailmessage = str_replace("{carmodel}", $carmodelandbrand, $emailmessage);
                $emailmessage = str_replace("{BrandName}", $brandname, $emailmessage);
                $emailmessage = str_replace("{PickupLocation}", $pickup_Location, $emailmessage);
                $emailmessage = str_replace("{DropoffLocation}", $drop_Location, $emailmessage);
                $emailmessage = str_replace("{bookingDate}", $booking_date, $emailmessage);
                $emailmessage = str_replace("{BookingTime}", $booking_time, $emailmessage);
                $emailmessage = str_replace("{PickupDate}", $pickupdate, $emailmessage);
                $emailmessage = str_replace("{PickupTime}", $pickuptime, $emailmessage);
                $emailmessage = str_replace("{BookingType}", $bookingtype, $emailmessage);
                $emailmessage = str_replace("{TripCharge}", $car_Price, $emailmessage);
                $emailmessage = str_replace("{coupondiscount}", $coupon_discount, $emailmessage);
                $emailmessage = str_replace("{TripTax}", $tax_amount, $emailmessage);
                $emailmessage = str_replace("{SubTotal}", $sub_total, $emailmessage);
                $emailmessage = str_replace("{PaymentMethod}", $payment_method, $emailmessage);
                $emailmessage = str_replace("{TotalAmount}", $final_amount, $emailmessage);

                //$response['EmailResponseSql'] = $emailmessage;
               $notifications= new NotificationsController();
               $response['EmailResponse'] = $notifications->sendEmail($to, $emailsubject,$emailmessage);

               
           // }
        }

                return response()->json($response);
    }

}
