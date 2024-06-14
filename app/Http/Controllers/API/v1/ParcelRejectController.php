<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\API\v1\GcmController;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ParcelOrder;
use DB;
use Illuminate\Http\Request;

class ParcelRejectController extends Controller
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
    public function rejectRequest(Request $request)
    {

        $id_parcel = $request->get('id_parcel');
        $id_user = $request->get('id_user');
        $driver_name = $request->get('name');
        $from_id = $request->get('from_id');
        $reason = $request->get('reason');
        $user_cat = $request->get('user_cat');
        if (!empty($id_parcel) && !empty($from_id) && !empty($driver_name) && !empty($id_user)) {

            $sql = ParcelOrder::where('id', $id_parcel)->first();
            $rejectDriverIds = $sql->rejected_driver_id;
            $rejDriverIds = array();
            if ($rejectDriverIds != null) {
                $rejDriverIds = json_decode($rejectDriverIds, true);
            }

            $row_sql = $sql->toArray();
            if ($row_sql['parcel_image'] != '') {
                if (file_exists(public_path('images/parcel_order' . '/' . $row_sql['parcel_image']))) {
                    $image_user = asset('images/parcel_order') . '/' . $row_sql['parcel_image'];
                } else {
                    $image_user = asset('assets/images/placeholder_image.jpg');

                }
                $row_sql['parcel_image'] = $image_user;
            }
            if ($user_cat == 'driver') {
                $title = str_replace("'", "\'", "Rejection of your Parcel");
                $msg = str_replace("'", "\'", $driver_name . " is rejected your parcel.");
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

                $driver_id = $row_sql['id_conducteur'];

                if (!in_array($driver_id, $rejDriverIds)) {
                        array_push($rejDriverIds, $driver_id);

                    }

                    $updateRejDriverArr = json_encode($rejDriverIds);
                    $updatedata = DB::update('update parcel_orders set status = ?,rejected_driver_id=? where id = ?', ['driver_rejected', $updateRejDriverArr, $id_parcel]);
                    $sql_update = ParcelOrder::where('id', '=', $id_parcel)->first();
                    $row = $sql_update->toArray();
                    $row['id'] = (string) $row['id'];
            } elseif ($user_cat == 'user_app') {
                $updatedata = DB::update('update parcel_orders set status = ? where id = ?', ['rejected', $id_parcel]);
                $sql_update = ParcelOrder::where('id', '=', $id_parcel)->first();
                $row = $sql_update->toArray();
                $row['id'] = (string) $row['id'];
                $tmsg = '';
                $terrormsg = '';

                $title = str_replace("'", "\'", "Cancellation of  parcel delivery");
                $msg = str_replace("'", "\'", $driver_name . " canceled the parcel delivery");
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
