<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\API\v1\GcmController;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Requests;
use DB;
use Illuminate\Http\Request;

class SetRejectedRequeteController extends Controller
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
    public function rejectedRequest(Request $request)
    {

        $id_requete = $request->get('id_ride');
        $id_user = $request->get('id_user');
        $driver_name = $request->get('name');
        $from_id = $request->get('from_id');
        $reason = $request->get('reason');
        $user_cat = $request->get('user_cat'); 
        if (!empty($id_requete) && !empty($from_id) && !empty($driver_name) && !empty($id_user)) {

            $sql = Requests::where('id', $id_requete)->first();
            $rejectDriverIds=$sql->rejected_driver_id;
            $rejDriverIds=array();
            if($rejectDriverIds!=null){
              $rejDriverIds=json_decode($rejectDriverIds,true);
            }

            $row_sql = $sql->toArray();
            if ($row_sql['trajet'] != '') {
                if (file_exists(public_path('images/recu_trajet_course' . '/' . $row_sql['trajet']))) {
                    $image_user = asset('images/recu_trajet_course') . '/' . $row_sql['trajet'];
                } else {
                    $image_user = asset('assets/images/placeholder_image.jpg');

                }
                $row_sql['trajet'] = $image_user;
            }
            if ($user_cat == 'driver') {
                $tmsg = '';
                $terrormsg = '';

                $title = str_replace("'", "\'", "Rejection of your ride");
                $msg = str_replace("'", "\'", $driver_name . " is cancelled your ride.");
                $reasons = str_replace("'", "\'", "$reason");

                $tab[] = array();
                $tab = explode("\\", $msg);
                $msg_ = "";
                for ($i = 0; $i < count($tab); $i++) {
                    $msg_ = $msg_ . "" . $tab[$i];
                }
                $message = array("body" => $msg_, "reasons" => $reasons, "title" => $title, "sound" => "mySound", "tag" => "riderejected");

                $query = DB::table('tj_user_app')
                    ->select('fcm_id')
                    ->where('fcm_id', '<>', '')
                    ->where('id', '=', $id_user)
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
                $data = $row_sql;
                if (count($tokens) > 0) {
                    GcmController::send_notification($tokens, $message, $data);
                }


                $lat = $row_sql['latitude_depart'];
                $long = $row_sql['longitude_depart'];
                $driver_id = $row_sql['id_conducteur'];
                
                $vehicleType = DB::table('tj_vehicule')->select('id_type_vehicule')->where('id_conducteur', $driver_id)->first();
                $settings = DB::table('tj_settings')->select('driver_radios','minimum_deposit_amount')->first();
                $radius=$settings->driver_radios;
                $minimum_wallet_balance=$settings->minimum_deposit_amount;
                $data = DB::table("tj_conducteur")
                    ->join('tj_vehicule', 'tj_vehicule.id_conducteur', '=', 'tj_conducteur.id')
                    ->select("tj_conducteur.id"
                        , DB::raw("3959  * acos(cos(radians(" . $lat . "))
            * cos(radians(tj_conducteur.latitude))
            * cos(radians(tj_conducteur.longitude) - radians(" . $long . "))
            + sin(radians(" . $lat . "))
            * sin(radians(tj_conducteur.latitude))) AS distance"))
                    ->having('distance', '<=', $radius)
                    ->orderBy('distance', 'asc')
                    ->where('tj_conducteur.statut', 'yes')
                    ->where('tj_conducteur.id','!=', $driver_id)
                    ->whereNotIn('tj_conducteur.id', $rejDriverIds)
                    ->where('tj_conducteur.is_verified', '=', '1')
                    ->where('tj_conducteur.online', '!=', 'no')
                    ->where('tj_conducteur.amount', '>=', $minimum_wallet_balance)
                    ->where('id_type_vehicule', '=', $vehicleType->id_type_vehicule)
                    ->first();
                if (!empty($data)) {
                
                        $id = $data->id;

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
                            ->where('id', '=', $id)
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
                        $data = $row_sql;
                        if (count($tokens) > 0) {
                            GcmController::send_notification($tokens, $message, $data);
                        }
                        if ($id) {
                            $date_heure = date('Y-m-d H:i:s');
                          if (!in_array($driver_id, $rejDriverIds)) {
                            array_push($rejDriverIds, $driver_id);
  
                           }

                            $updateRejDriverArr=json_encode($rejDriverIds);
                            $updatedata = DB::update('update tj_requete set statut = ?,updated_at=?,id_conducteur = ?,rejected_driver_id=? where id = ?', ['new', $date_heure,$id,$updateRejDriverArr,$id_requete]);
                            $sql_update = Requests::orderBy('updated_at', 'DESC')->first();
                            $row = $sql_update->toArray();
                            $row['id']=(string)$row['id'];

                    }
                } else {

                    if (!in_array($driver_id, $rejDriverIds)) {
                        array_push($rejDriverIds, $driver_id);

                    }

                    $updateRejDriverArr=json_encode($rejDriverIds);
                    $updatedata = DB::update('update tj_requete set statut = ?,rejected_driver_id=? where id = ?', ['driver_rejected',$updateRejDriverArr, $id_requete]);
                    $sql_update = Requests::where('id','=',$id_requete)->first();
                    $row = $sql_update->toArray();
                    $row['id']=(string)$row['id'];
                }
                
            }
            elseif ($user_cat == 'user_app') {
                $updatedata = DB::update('update tj_requete set statut = ? where id = ?', ['rejected', $id_requete]);
                $sql_update = Requests::where('id','=',$id_requete)->first();
                $row = $sql_update->toArray();
                $row['id']=(string)$row['id'];
                $tmsg = '';
                $terrormsg = '';

                $title = str_replace("'", "\'", "Cancellation of  ride");
                $msg = str_replace("'", "\'", $driver_name . " canceled the ride");
                $reasons = str_replace("'", "\'", "$reason");

                $tab[] = array();
                $tab = explode("\\", $msg);
                $msg_ = "";
                for ($i = 0; $i < count($tab); $i++) {
                    $msg_ = $msg_ . "" . $tab[$i];
                }


                $message = array("body" => $msg_, "reasons" => $reasons, "title" => $title, "sound" => "mySound", "tag" => "riderejected");

                $query = DB::table('tj_conducteur')
                    ->select('fcm_id')
                    ->where('fcm_id', '<>', '')
                    ->where('id', '=', $id_user)
                    ->get();
            }
            $tokens = array();
            if (!empty($query)) {
                foreach ($query as $user) {
                    if (!empty($user->fcm_id)) {
                        $tokens[] = $user->fcm_id;
                    }
                }
            }
            $temp = array();
            $data = $row;
            if (count($tokens) > 0) {
                GcmController::send_notification($tokens, $message, $data);
                $date_heure = date('Y-m-d H:i:s');
                $from_id = $request->get('from_id');
                $to_id = $request->get('id_user');

                $insertdata = DB::insert("insert into tj_notification(titre,message,statut,creer,modifier,to_id,from_id,type)
                values('" . $title . "','" . $msg . "','yes','" . $date_heure . "','" . $date_heure . "','" . $to_id . "','" . $from_id . "','riderejected')");
                $sql_notification = Notification::orderby('id', 'desc')->first();
                $data = $sql_notification->toArray();
                $row['titre'] = $data['titre'];
                $row['message'] = $data['message'];
                $row['reason'] = $reason;
                $row['statut_notification'] = $data['statut'];
                $row['to_id'] = $data['to_id'];
                $row['from_id'] = $data['from_id'];
                $row['type'] = $data['type'];
            }

            $response['success'] = 'success';
            $response['error'] = null;
            $response['message'] = 'status successfully updated';
            $response['data'] = $row;


        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'some fields are missing';

        }
        return response()->json($response);
    }


}
