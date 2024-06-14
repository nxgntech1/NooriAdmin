<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\API\v1\GcmController;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ParcelOrder;
use App\Models\Driver;
use DB;
use Illuminate\Http\Request;

class ParcelConfirmController extends Controller
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
    public function confirmRequest(Request $request)
    {

        $id_parcel = $request->get('id_parcel');
        $id_user = $request->get('id_user');
        $driver_name = $request->get('driver_name');
        $driver_id = $request->get('driver_id');

        if (!empty($id_parcel) && !empty($id_user) && !empty($driver_name) && !empty($driver_id)) {
            $updatedata = ParcelOrder::where('id', $id_parcel)->update(['status' => 'confirmed', 'id_conducteur' => $driver_id]);

            if (!empty($updatedata)) {
                $otp = random_int(100000, 999999);
                $parcelOrder = ParcelOrder::where('id', $id_parcel)->first();
                if ($parcelOrder) {
                    $parcelOrder->otp = $otp;
                }
                $parcelOrder->save();
                $sql = ParcelOrder::where('id', $id_parcel)->first();
                $row = $sql->toArray();
                $row['id'] = (string)$row['id'];
                if ($row['parcel_image'] != '') {
                    $parcelImage = json_decode($row['parcel_image'], true);
                    $image_user = [];
                    foreach ($parcelImage as $value) {
                        if (file_exists(public_path('images/parcel_order/' . '/' . $value))) {
                            $image = asset('images/parcel_order/') . '/' . $value;
                        }
                        array_push($image_user, $image);
                    }
                    if (!empty($image_user)) {
                        $row['parcel_image'] = $image_user;
                    } else {
                        $image_user = asset('assets/images/placeholder_image.jpg');
                    }

                }
                $title = str_replace("'", "\'", "Confirmation of your parcel order");
                $msg = str_replace("'", "\'", $driver_name . " is Confirmed your parcel order.");

                $tab[] = array();
                $tab = explode("\\", $msg);
                $msg_ = "";
                for ($i = 0; $i < count($tab); $i++) {
                    $msg_ = $msg_ . "" . $tab[$i];
                }
                $message = array("body" => $msg_, "title" => $title, "sound" => 'mySound', "tag" => "parcelconfirmed");

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
                if (count($tokens) > 0) {
                    GcmController::send_notification($tokens, $message, $temp);
                    $date_heure = date('Y-m-d H:i:s');
                    $to_id = $request->get('id_user');

                    $insertdata = DB::insert("insert into tj_notification(titre,message,statut,creer,modifier,to_id,from_id,type)
            values('" . $title . "','" . $msg . "','yes','" . $date_heure . "','" . $date_heure . "','" . $to_id . "','" . $driver_id . "','rideconfirmed')");
                    $sql_notification = Notification::orderby('id', 'desc')->first();
                    $data = $sql_notification->toArray();
                    $row['titre'] = $data['titre'];
                    $row['message'] = $data['message'];
                    $row['statut_notification'] = $data['statut'];
                    $row['to_id'] = $data['to_id'];
                    $row['from_id'] = $data['from_id'];
                    $row['type'] = $data['type'];

                    $driver_data = Driver::where('id', $driver_id)->first();
                    $driver = $driver_data->toArray();
                    $row['driver_id'] = (string)$driver['id'];
                    $row['driver_name'] = (string)$driver_name;
                    $row['driver_phone'] = (string)$driver['phone'];

                }
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
            $response['error'] = 'some field are missing';

        }
        return response()->json($response);
    }


}
