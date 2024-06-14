<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\API\v1\GcmController;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Notification;
use App\Models\ParcelOrder;
use DB;
use Illuminate\Http\Request;

class ParcelCompleteController extends Controller
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
    public function completeRequest(Request $request)
    {
        $months = array("January" => 'Jan', "February" => 'Feb', "March" => 'Mar', "April" => 'Apr', "May" => 'May', "June" => 'Jun', "July" => 'Jul', "August" => 'Aug', "September" => 'Sep', "October" => 'Oct', "November" => 'Nov', "December" => 'Dec');

        $id_parcel = $request->get('id_parcel');
        $id_user = $request->get('id_user');
        $driver_name = $request->get('driver_name');
        $from_id = $request->get('from_id');
        $date_heure = date('Y-m-d 00:00:00');
        if (!empty($id_parcel) && !empty($driver_name) && !empty($id_user) && !empty($from_id)) {

            $updatedata = ParcelOrder::where('id', $id_parcel)->update(['status' => 'completed']);

            if (!empty($updatedata)) {
                $sql = ParcelOrder::where('id', $id_parcel)->first();
                $row = $sql->toArray();
                $row['id'] = (string)$row['id'];

                $row['created_at'] = date("d", strtotime($row['created_at'])) . " " . $months[date("F", strtotime($row['created_at']))] . ", " . date("Y", strtotime($row['created_at']));

                if ($row['parcel_image'] != '') {
                    if (file_exists(public_path('images/parcel_order' . '/' . $row['parcel_image']))) {
                        $image_user = asset('images/parcel_order') . '/' . $row['parcel_image'];
                    } else {
                        $image_user = asset('assets/images/placeholder_image.jpg');

                    }
                    $row['parcel_image'] = $image_user;
                }

                $driver = Driver::where('id', $row['id_conducteur'])->first();
                $row['prenomConducteur'] = $driver->prenom;
                $row['nomConducteur'] = $driver->nom;
                $row['photo_path'] = $driver->photo_path;
                if ($row['photo_path'] != '') {
                    if (file_exists(public_path('assets/images/driver' . '/' . $row['photo_path']))) {
                        $image_user = asset('assets/images/driver') . '/' . $row['photo_path'];
                    } else {
                        $image_user = asset('assets/images/placeholder_image.jpg');

                    }
                } else {
                    $image_user = asset('assets/images/placeholder_image.jpg');

                }
                $row['photo_path'] = $image_user;

                $sql_nb_avis = DB::table('tj_note')
                    ->select(DB::raw("COUNT(id) as nb_avis"), DB::raw("SUM(niveau) as somme"))
                    ->where('id_conducteur', '=', $row['id_conducteur'])
                    ->get();

                if (!empty($sql_nb_avis)) {
                    foreach ($sql_nb_avis as $row_nb_avis)
                        $somme = $row_nb_avis->somme;
                    $nb_avis = $row_nb_avis->nb_avis;
                    if ($nb_avis != "0")
                        $moyenne = $somme / $nb_avis;
                    else
                        $moyenne = 0;
                } else {
                    $somme = "0";
                    $nb_avis = "0";
                    $moyenne = 0;
                }
                $row['moyenne'] = $moyenne;

                $title = str_replace("'", "\'", "Parcel delivered");
                $msg = str_replace("'", "\'", $driver_name . " is delivered your parcel.");

                $tab[] = array();
                $tab = explode("\\", $msg);
                $msg_ = "";
                for ($i = 0; $i < count($tab); $i++) {
                    $msg_ = $msg_ . "" . $tab[$i];
                }
                $message = array("body" => $msg_, "title" => $title, "sound" => "mySound", "tag" => "ridecompleted");

                $query = DB::table('tj_user_app')
                    ->select('fcm_id', 'nom', 'prenom', 'email')
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
                $data = $row;
                if (count($tokens) > 0) {
                    GcmController::send_notification($tokens, $message, $row);
                    $date_heure = date('Y-m-d H:i:s');
                    $to_id = $request->get('id_user');
                    $insertdata = DB::insert("insert into tj_notification(titre,message,statut,creer,modifier,to_id,from_id,type)
            values('" . $title . "','" . $msg . "','yes','" . $date_heure . "','" . $date_heure . "','" . $to_id . "','" . $from_id . "','ridecompleted')");
                    $sql_notification = Notification::orderby('modifier', 'desc')->first();
                    $data = $sql_notification->toArray();
                    $row['titre'] = $data['titre'];
                    $row['message'] = $data['message'];
                    $row['statut_notification'] = $data['statut'];
                    $row['to_id'] = $data['to_id'];
                    $row['from_id'] = $data['from_id'];
                    $row['type'] = $data['type'];
                }
                $row['tax'] = json_decode($row['tax'], true);

                $response['success'] = 'success';
                $response['error'] = null;
                $response['message'] = 'status successfully updated';
                $response['data'] = $row;

            } else {
                $response['success'] = 'Failed';
                $response['error'] = 'Failed to update data';
            }
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'some fields are missing';
        }
        return response()->json($response);
    }


}
