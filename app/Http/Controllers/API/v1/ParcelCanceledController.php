<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\ParcelOrder;
use Illuminate\Http\Request;
use DB;

class ParcelCanceledController extends Controller
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

    public function cancelRequest(Request $request)
    {
        $id_requete = $request->get('parcel_id');
        $reason = $request->get('reason');
        $updatedata = DB::update('update parcel_orders set status = ?,reason = ? where id = ?', ['canceled',$reason,$id_requete]);

        if (!empty($updatedata)) {

            $sql = ParcelOrder::where('id', $id_requete)->first();
            $row = $sql->toArray();
            $row['id'] = (string) $row['id'];
            $row['tax'] = json_decode($row['tax'], true);

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