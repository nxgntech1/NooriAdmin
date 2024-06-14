<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\GcmController;
use App\Models\Requests;
use Illuminate\Http\Request;
use DB;

class cancelRequeteController extends Controller
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
    
    public function cancel(Request $request)
    {
        $id_requete = $request->get('id_ride');
        $id_driver = $request->get('id_driver');
        $user_name = $request->get('user_name');

        $updatedata = DB::update('update tj_requete set statut = ? where id = ?', ['canceled', $id_requete]);

        if (!empty($updatedata)) {

            $sql = Requests::where('id', $id_requete)->first();
            $rejectDriverIds = $sql->rejected_driver_id;
            $rejDriverIds = array();
            if ($rejectDriverIds != null) {
                $rejDriverIds = json_decode($rejectDriverIds, true);
            }
            $row = $sql->toArray();
            $row['id'] = (string) $row['id'];
            $row['stops'] = json_decode($row['stops'], true);
            $row['tax'] = json_decode($row['tax'], true);
            $row['user_info'] = json_decode($row['user_info'], true);
            
            if ($row['trajet'] != '') {
                if (file_exists(public_path('images/recu_trajet_course' . '/' . $row['trajet']))) {
                    $image_user = asset('images/recu_trajet_course') . '/' . $row['trajet'];
                } else {
                    $image_user = asset('assets/images/placeholder_image.jpg');

                }
                $row['trajet'] = $image_user;
            }
            $tmsg = '';
            $terrormsg = '';

            $title = str_replace("'", "\'", "Cancellation of a ride");
            $msg = str_replace("'", "\'", $user_name . " canceled his ride");

            $tab[] = array();
            $tab = explode("\\", $msg);
            $msg_ = "";
            for ($i = 0; $i < count($tab); $i++) {
                $msg_ = $msg_ . "" . $tab[$i];
            }


            $message = array("body" => $msg_, "title" => $title, "sound" => "mySound", "tag" => "ridecanceledrider");

            $query = DB::table('tj_conducteur')
                ->select('fcm_id')
                ->where('fcm_id', '<>', '')
                ->where('id', '=', $id_driver)
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
            $data = $row;
            if (count($tokens) > 0) {
                GcmController::send_notification($tokens, $message, $data);
            }

            // auto assign driver
            $lat = $row['latitude_depart'];
            $long = $row['longitude_depart'];
            $settings = DB::table('tj_settings')->select('driver_radios')->first();
            $radius = $settings->driver_radios;
            $data = DB::table("tj_conducteur")
                ->select(
                    "tj_conducteur.id"
                    , DB::raw("3959  * acos(cos(radians(" . $lat . "))
            * cos(radians(tj_conducteur.latitude))
            * cos(radians(tj_conducteur.longitude) - radians(" . $long . "))
            + sin(radians(" . $lat . "))
            * sin(radians(tj_conducteur.latitude))) AS distance")
                )
                ->having('distance', '<=', $radius)
                ->orderBy('distance', 'asc')
                ->where('tj_conducteur.statut', 'yes')
                ->where('tj_conducteur.id', '!=', $id_driver)
                ->whereNotIn('tj_conducteur.id', $rejDriverIds)
                ->get();
            
            foreach ($data as $val) {
                $id = $val->id;

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
                $data = $row;
                if (count($tokens) > 0) {
                    GcmController::send_notification($tokens, $message, $row);
                }
                if ($id) {
                    array_push($rejDriverIds, $id_driver);
                    $updateRejDriverArr = json_encode($rejDriverIds);
                    $updatedata = DB::update('update tj_requete set statut = ?,id_conducteur = ?,rejected_driver_id=? where id = ?', ['new', $id, $updateRejDriverArr, $id_requete]);
                }
            }

            $response['success'] = 'success';
            $response['error'] = null;
            $response['message'] = 'successfully';
            $response['data'] = $row;
        } else {
            $response['success'] = 'failed';
            $response['error'] = 'failed to update';
        }
        return response()->json($response);
    }

}